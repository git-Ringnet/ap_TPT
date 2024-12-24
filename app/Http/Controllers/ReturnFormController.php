<?php

namespace App\Http\Controllers;

use App\Models\Customers;
use App\Models\Product;
use App\Models\ProductReturn;
use App\Models\Quotation;
use App\Models\Receiving;
use App\Models\ReturnForm;
use App\Models\SerialNumber;
use App\Models\warrantyLookup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ReturnFormController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $returnforms = ReturnForm::with(['reception', 'customer', 'productReturns'])->get();
        $title = 'Phiếu trả hàng';
        return view('expertise.returnforms.index', compact('returnforms', 'title'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $quoteNumber = (new Receiving)->getQuoteCount('PTH', ReturnForm::class, 'return_code');
        $title = 'Tạo phiếu trả hàng';
        $receivings = Receiving::all();
        return view('expertise.returnforms.create', compact('quoteNumber', 'title', 'receivings'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate dữ liệu đầu vào
        $validated = $request->validate([
            'reception_id' => 'required',
            'return_code' => 'required|unique:return_form,return_code',
            'customer_id' => 'required|exists:customers,id',
            'address' => 'nullable|string',
            'date_created' => 'required|date',
            'contact_person' => 'nullable|string',
            'return_method' => 'required|in:1,2,3',
            'user_id' => 'required|string',
            'phone_number' => 'nullable|string',
            'notes' => 'nullable|string',
            'status' => 'required|in:1,2',
            'return' => 'required|array',
            'return.*.product_id' => 'required|exists:products,id',
            'return.*.quantity' => 'required|integer|min:1',
            'return.*.serial_code' => 'nullable|string',
            'return.*.serial_id' => 'required',
            'return.*.replacement_code' => 'nullable|integer',
            'return.*.replacement_serial_number_id' => 'nullable|string',
            'return.*.extra_warranty' => 'nullable|integer|min:0',
            'return.*.note' => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
            // Tạo bản ghi return_form
            $returnForm = ReturnForm::create($validated);
            // Lặp qua danh sách "return"
            foreach ($validated['return'] as $returnItem) {
                $replacementSerialId = null;
                // Tìm serial thay thế (nếu có) và lấy export_return_date và warranty từ serial cũ
                if (!empty($returnItem['replacement_serial_number_id'])) {
                    $seriRecord = SerialNumber::where('serial_code', $returnItem['replacement_serial_number_id'])
                        ->where('product_id', $returnItem['replacement_code'])
                        ->where('status', 1)
                        ->first();

                    if (!$seriRecord) {
                        DB::rollBack();
                        return back()->withErrors([
                            'error' => "Không tìm thấy serial_code '{$returnItem['replacement_serial_number_id']}' trong bảng seri."
                        ])->withInput();
                    }

                    // Cập nhật trạng thái của serial thay thế thành 5
                    $seriRecord->update(['status' => 5]);
                    $replacementSerialId = $seriRecord->id;

                    // Thêm vào bảng warranty_lookup cho serial thay thế
                    $oldWarrantyLookup = warrantyLookup::where('sn_id', $returnItem['serial_id'])->first();
                    // dd($oldWarrantyLookup);
                    if ($oldWarrantyLookup) {
                        WarrantyLookup::create([
                            'product_id' => $returnItem['product_id'],
                            'sn_id' => $replacementSerialId,
                            'customer_id' => $validated['customer_id'],
                            'export_return_date' => $oldWarrantyLookup->export_return_date,
                            'warranty' => $oldWarrantyLookup->warranty + ($returnItem['extra_warranty'] ?? 0),
                            'status' => 0,
                        ]);
                    }
                } else {
                    // Nếu không có seri thay thế thì cập nhật vào seri cũ thêm thời gian bảo hành
                    $WarrantyLookup = warrantyLookup::where('sn_id', $returnItem['serial_id'])->first();
                    if ($WarrantyLookup) {
                        $WarrantyLookup->update([
                            'warranty' => $WarrantyLookup->warranty + ($returnItem['extra_warranty'] ?? 0),
                        ]);
                    }
                }
                // Cập nhật trạng thái của serial chính thành 4
                $serial = SerialNumber::find($returnItem['serial_id']);
                if ($serial) {
                    $serial->update(['status' => 4]);
                } else {
                    DB::rollBack();
                    return back()->withErrors([
                        'error' => "Không tìm thấy serial_id '{$returnItem['serial_id']}' trong bảng seri."
                    ])->withInput();
                }
                // Tạo bản ghi product_return
                ProductReturn::create([
                    'return_form_id' => $returnForm->id,
                    'product_id' => $returnItem['product_id'],
                    'quantity' => $returnItem['quantity'],
                    'serial_number_id' => $returnItem['serial_id'],
                    'replacement_code' => $returnItem['replacement_code'],
                    'replacement_serial_number_id' => $replacementSerialId,
                    'extra_warranty' => $returnItem['extra_warranty'],
                    'notes' => $returnItem['note'],
                ]);
                $stateRecei = $validated['status'] = 1 ? 3 : 4;
                Receiving::find($validated['reception_id'])->update(['status' => $stateRecei]);
            }

            DB::commit();
            return redirect()->route('returnforms.index')->with('msg', 'Tạo phiếu trả hàng thành công');
        } catch (\Exception $e) {
            DB::rollBack();
            // Ghi lỗi vào log
            Log::error('Error creating return form: ' . $e->getMessage());
            // Gửi thông báo lỗi về view
            return back()->withErrors(['error' => 'Có lỗi xảy ra: ' . $e->getMessage()])->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(ReturnForm $returnForm)
    {
        return view('expertise.returnforms.show', compact('returnForm'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $returnForm = ReturnForm::with('productReturns', 'reception')->findOrFail($id);
        $title = 'Chi tiết phiếu trả hàng';
        $receivings = Receiving::all();
        $returnProducts = $returnForm->productReturns->keyBy('id');
        // dd($returnProducts);
        $dataProduct = Product::all();
        return view('expertise.returnforms.edit', compact('returnForm',  'title', 'receivings', 'returnProducts', 'dataProduct'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // Tìm bản ghi return form
        $returnForm = ReturnForm::findOrFail($id);
        // Xác thực dữ liệu đầu vào
        $validated = $request->validate([
            'reception_id' => 'required',
            'return_code' => 'required|unique:return_form,return_code,' . $returnForm->id,
            'customer_id' => 'required|exists:customers,id',
            'address' => 'nullable|string',
            'date_created' => 'required|date',
            'contact_person' => 'nullable|string',
            'return_method' => 'required|in:1,2,3',
            'user_id' => 'required|string',
            'phone_number' => 'nullable|string',
            'notes' => 'nullable|string',
            'status' => 'required|in:1,2',
            'return' => 'required|array', // Đảm bảo mảng "return" tồn tại
            'return.*.product_id' => 'required|exists:products,id',
            'return.*.quantity' => 'required|integer|min:1',
            'return.*.serial_code' => 'nullable|string',
            'return.*.serial_id' => 'required',
            'return.*.replacement_code' => 'nullable|integer',
            'return.*.replacement_serial_number_id' => 'nullable|string',
            'return.*.extra_warranty' => 'nullable|integer|min:0',
            'return.*.note' => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
            // Cập nhật bản ghi return_form
            $returnForm->update($validated);
            // Xóa các bản ghi product_return cũ liên quan
            ProductReturn::where('return_form_id', $returnForm->id)->delete();
            // Lặp qua danh sách "return" để xử lý từng sản phẩm
            foreach ($validated['return'] as $returnItem) {
                $replacementSerialId = null;

                // Xử lý serial thay thế nếu tồn tại
                if (!empty($returnItem['replacement_serial_number_id'])) {
                    $seriRecord = SerialNumber::where('serial_code', $returnItem['replacement_serial_number_id'])
                        ->where('product_id', $returnItem['replacement_code'])
                        ->where('status', 1)
                        ->first();

                    if (!$seriRecord) {
                        DB::rollBack();
                        return back()->withErrors([
                            'error' => "Không tìm thấy serial_code '{$returnItem['replacement_serial_number_id']}' trong bảng seri."
                        ])->withInput();
                    }

                    // Cập nhật trạng thái của serial thay thế thành 5
                    $seriRecord->update(['status' => 5]);
                    $replacementSerialId = $seriRecord->id;

                    // Cập nhật/Thêm bản ghi vào warranty_lookup
                    $oldWarrantyLookup = WarrantyLookup::where('sn_id', $returnItem['serial_id'])->first();
                    if ($oldWarrantyLookup) {
                        WarrantyLookup::create([
                            'product_id' => $returnItem['product_id'],
                            'sn_id' => $replacementSerialId,
                            'customer_id' => $validated['customer_id'],
                            'export_return_date' => $oldWarrantyLookup->export_return_date,
                            'warranty' => $oldWarrantyLookup->warranty + ($returnItem['extra_warranty'] ?? 0),
                            'status' => 0,
                        ]);
                    }
                } else {
                    // Nếu không có serial thay thế, cập nhật warranty cho serial hiện tại
                    $warrantyLookup = WarrantyLookup::where('sn_id', $returnItem['serial_id'])->first();
                    if ($warrantyLookup) {
                        $warrantyLookup->update([
                            'warranty' => $warrantyLookup->warranty + ($returnItem['extra_warranty'] ?? 0),
                        ]);
                    }
                }
                // Cập nhật trạng thái của serial chính thành 4
                $serial = SerialNumber::find($returnItem['serial_id']);
                if ($serial) {
                    $serial->update(['status' => 4]);
                } else {
                    DB::rollBack();
                    return back()->withErrors([
                        'error' => "Không tìm thấy serial_id '{$returnItem['serial_id']}' trong bảng seri."
                    ])->withInput();
                }
                // Tạo mới bản ghi product_return
                ProductReturn::create([
                    'return_form_id' => $returnForm->id,
                    'product_id' => $returnItem['product_id'],
                    'quantity' => $returnItem['quantity'],
                    'serial_number_id' => $returnItem['serial_id'],
                    'replacement_code' => $returnItem['replacement_code'],
                    'replacement_serial_number_id' => $replacementSerialId,
                    'extra_warranty' => $returnItem['extra_warranty'],
                    'notes' => $returnItem['note'],
                ]);
            }
            // Cập nhật trạng thái của Receiving dựa trên trạng thái hiện tại
            $stateRecei = $validated['status'] == 1 ? 3 : 4;
            Receiving::find($validated['reception_id'])->update(['status' => $stateRecei]);

            DB::commit();
            return redirect()->route('returnforms.index')->with('msg', 'Cập nhật phiếu trả hàng thành công');
        } catch (\Exception $e) {
            DB::rollBack();
            // Ghi lỗi vào log
            Log::error('Error updating return form: ' . $e->getMessage());
            // Gửi thông báo lỗi về view
            return back()->withErrors(['error' => 'Có lỗi xảy ra: ' . $e->getMessage()])->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ReturnForm $returnForm)
    {
        $returnForm->delete();
        return redirect()->route('returnforms.index')->with('msg', 'Return form deleted successfully!');
    }
}
