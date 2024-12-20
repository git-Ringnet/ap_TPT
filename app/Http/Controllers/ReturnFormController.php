<?php

namespace App\Http\Controllers;

use App\Models\Customers;
use App\Models\Product;
use App\Models\ProductReturn;
use App\Models\Quotation;
use App\Models\Receiving;
use App\Models\ReturnForm;
use App\Models\SerialNumber;
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

                // Tìm serial và cập nhật trạng thái nếu cần
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

                    // Cập nhật trạng thái của serial thành 4
                    $replacementSerialId = $seriRecord->id;
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
        // Xác thực dữ liệu đầu vào
        $returnForm = ReturnForm::findOrFail($id);
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
            $returnForm->update($validated);
            ProductReturn::where('return_form_id', $returnForm->id)->delete();
            foreach ($validated['return'] as $returnItem) {
                $replacementSerialId = null;
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
                    $replacementSerialId = $seriRecord->id;
                }
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
