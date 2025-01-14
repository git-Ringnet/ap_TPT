<?php

namespace App\Http\Controllers;

use App\Models\Customers;
use App\Models\Product;
use App\Models\ProductReturn;
use App\Models\Quotation;
use App\Models\Receiving;
use App\Models\ReturnForm;
use App\Models\SerialNumber;
use App\Models\User;
use App\Models\warrantyLookup;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ReturnFormController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    private $returnforms;

    public function __construct(ReturnForm $returnforms)
    {
        $this->returnforms = $returnforms;
    }
    public function index()
    {
        $returnforms = ReturnForm::with(['reception', 'customer', 'productReturns'])->get();
        $title = 'Phiếu trả hàng';
        $customers = Customers::all();
        return view('expertise.returnforms.index', compact('returnforms', 'title', 'customers'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $quoteNumber = (new Receiving)->getQuoteCount('PTH', ReturnForm::class, 'return_code');
        $title = 'Tạo phiếu trả hàng';
        $existingReceptionIds = ReturnForm::pluck('reception_id')->toArray();
        $receivings = Receiving::whereNotIn('id', $existingReceptionIds)->where('status', 2)->get();
        return view('expertise.returnforms.create', compact('quoteNumber', 'title', 'receivings'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate dữ liệu đầu vào
        $validated = $request->validate([
            'reception_id' => 'required|unique:return_form,reception_id',
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
                    if ($oldWarrantyLookup) {
                        $WarrantyLookup =  WarrantyLookup::create([
                            'product_id' => $returnItem['product_id'],
                            'sn_id' => $replacementSerialId,
                            'customer_id' => $validated['customer_id'],
                            'export_return_date' => $oldWarrantyLookup->export_return_date,
                            'warranty' => $oldWarrantyLookup->warranty + ((int)$returnItem['extra_warranty'] ?? 0),
                            'warranty_expire_date' => Carbon::parse($oldWarrantyLookup->warranty_expire_date)
                                ->addMonths((int) ($returnItem['extra_warranty'] ?? 0)),
                            'status' => 0,

                        ]);
                        $today = Carbon::now();
                        if ($today->greaterThanOrEqualTo($WarrantyLookup->warranty_expire_date)) {
                            $WarrantyLookup->update(['status' => 1]);
                        }
                    }
                } else {
                    // Nếu không có seri thay thế thì cập nhật vào seri cũ thêm thời gian bảo hành
                    $WarrantyLookup = warrantyLookup::where('sn_id', $returnItem['serial_id'])->first();
                    if ($WarrantyLookup) {
                        // Nếu tìm thấy, cập nhật thông tin
                        $WarrantyLookup->update([
                            'warranty' => $returnItem['extra_warranty'] ?? 0, // Gán số tháng bảo hành
                            'export_return_date' => Carbon::parse($validated['date_created']), // Ngày tạo phiếu trả
                            'warranty_expire_date' => Carbon::parse($validated['date_created'])
                                ->addMonths((int) ($returnItem['extra_warranty'] ?? 0)), // Ngày hết hạn bảo hành
                            'status' => 0,
                        ]);
                        $today = Carbon::now();
                        if ($today->greaterThanOrEqualTo($WarrantyLookup->warranty_expire_date)) {
                            $WarrantyLookup->update(['status' => 1]);
                        }
                    } else {
                        // Nếu không tìm thấy, tạo mới bản ghi
                        $WarrantyLookup = warrantyLookup::create([
                            'product_id' => $returnItem['product_id'],
                            'sn_id' => $returnItem['serial_id'],
                            'customer_id' => $validated['customer_id'],
                            'export_return_date' => $validated['date_created'],
                            'warranty' => $returnItem['extra_warranty'] ?? 0,
                            'warranty_expire_date' =>
                            Carbon::parse($validated['date_created'])
                                ->addMonths((int) ($returnItem['extra_warranty'] ?? 0)),
                            'status' => 0,
                        ]);
                    }
                    // Kiểm tra lại trạng thái của warranty sau khi tạo hoặc cập nhật
                    $today = Carbon::now();
                    if ($today->greaterThanOrEqualTo($WarrantyLookup->warranty_expire_date)) {
                        $WarrantyLookup->update(['status' => 1]);
                    }
                }
                // Cập nhật trạng thái của serial chính thành 4
                $serial = SerialNumber::find($returnItem['serial_id']);
                if ($serial) {
                    $serial->update(['status' => 4]);
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
                Receiving::find($validated['reception_id'])->update([
                    'status' => $stateRecei,
                    'state' => 0,
                    'closed_at' => $validated['return'] ? now() : null,
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
        // Lấy thông tin phiếu trả hàng và quan hệ với productReturns và reception
        $returnForm = ReturnForm::with('productReturns', 'reception')->findOrFail($id);
        $title = 'Chi tiết phiếu trả hàng';
        // Lấy tất cả các reception_id đã có trong bảng return_form
        $existingReceptionIds = ReturnForm::pluck('reception_id')->toArray();
        // Lọc ra tất cả các receiving, nhưng giữ lại reception_id của returnForm hiện tại
        $receivings = Receiving::whereNotIn('id', $existingReceptionIds)
            ->orWhere('id', $returnForm->reception_id)
            ->get();
        // Các thông tin khác liên quan đến phiếu trả hàng
        $returnProducts = $returnForm->productReturns->keyBy('id');
        $dataProduct = Product::all();
        $data = ReturnForm::with('productReturns', 'reception')->get();
        $customers = Customers::all();
        $users = User::all();

        // Trả về view với các dữ liệu đã chuẩn bị
        return view('expertise.returnforms.edit', compact('returnForm', 'title', 'receivings', 'returnProducts', 'dataProduct', 'data', 'customers', 'users'));
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
            'reception_id' => 'required|unique:return_form,reception_id,' . $returnForm->id,
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
                        $WarrantyLookup = WarrantyLookup::create([
                            'product_id' => $returnItem['product_id'],
                            'sn_id' => $replacementSerialId,
                            'customer_id' => $validated['customer_id'],
                            'export_return_date' => $oldWarrantyLookup->export_return_date,
                            'warranty' => $oldWarrantyLookup->warranty + ($returnItem['extra_warranty'] ?? 0),
                            'warranty_expire_date' => Carbon::parse($oldWarrantyLookup->warranty_expire_date)
                                ->addMonths((int) ($returnItem['extra_warranty'] ?? 0)),
                            'status' => 0,

                        ]);
                        $today = Carbon::now();
                        if ($today->greaterThanOrEqualTo($WarrantyLookup->warranty_expire_date)) {
                            $WarrantyLookup->update(['status' => 1]);
                        }
                    }
                } else {
                    // Nếu không có serial thay thế, cập nhật warranty cho serial hiện tại
                    $WarrantyLookup = WarrantyLookup::where('sn_id', $returnItem['serial_id'])->first();
                    if ($WarrantyLookup) {
                        $WarrantyLookup->update([
                            'warranty' => $returnItem['extra_warranty'] ?? 0, // Gán số tháng bảo hành
                            'export_return_date' => Carbon::parse($validated['date_created']), // Ngày tạo phiếu trả
                            'warranty_expire_date' => Carbon::parse($validated['date_created'])
                                ->addMonths((int) ($returnItem['extra_warranty'] ?? 0)), // Ngày hết hạn bảo hành
                            'status' => 0,
                        ]);
                        $today = Carbon::now();
                        if ($today->greaterThanOrEqualTo($WarrantyLookup->warranty_expire_date)) {
                            $WarrantyLookup->update(['status' => 1]);
                        }
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
            Receiving::find($validated['reception_id'])->update(['status' => $stateRecei, 'state' => 0, 'closed_at' => $validated['return'] ? now() : null,]);

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
    public function destroy(string $id)
    {
        // Tìm phiếu trả hàng và liên kết của nó
        $returnForm = ReturnForm::findOrFail($id);

        // Trước khi xóa phiếu trả hàng, cần phải giảm giá trị warranty của các sản phẩm trong phiếu trả hàng
        $returnForm->productReturns->each(function ($returnItem) {
            // Tìm WarrantyLookup tương ứng với serial_id của sản phẩm
            $WarrantyLookup = WarrantyLookup::where('sn_id', $returnItem->serial_number_id)->first();
            if ($WarrantyLookup) {
                // Trừ đi thời gian bảo hành (warranty) và ngày hết hạn bảo hành (warranty_expire_date)
                $newWarranty = $WarrantyLookup->warranty - ($returnItem->extra_warranty ?? 0);
                $newWarrantyExpireDate = Carbon::parse($WarrantyLookup->warranty_expire_date)
                    ->subMonths((int) ($returnItem->extra_warranty ?? 0));
                // Kiểm tra nếu newWarranty <= 0 thì xoá WarrantyLookup
                if ($newWarranty <= 0) {
                    $WarrantyLookup->delete();
                } else {
                    // Cập nhật WarrantyLookup với giá trị mới
                    $WarrantyLookup->update([
                        'warranty' => $newWarranty,
                        'warranty_expire_date' => $newWarrantyExpireDate,
                    ]);
                }
            }
        });

        // Xóa phiếu trả hàng và các sản phẩm liên quan
        $returnForm->delete();
        $returnForm->productReturns()->delete();
        // Trả về thông báo và chuyển hướng
        return redirect()->route('returnforms.index')->with('msg', 'Xoá phiếu trả hàng thành công!');
    }

    public function filterData(Request $request)
    {
        $data = $request->all();
        $filters = [];
        if (isset($data['ma']) && $data['ma'] !== null) {
            $filters[] = ['value' => 'Mã phiếu: ' . $data['ma'], 'name' => 'ma-phieu', 'icon' => 'po'];
        }
        if (isset($data['receiving_code']) && $data['receiving_code'] !== null) {
            $filters[] = ['value' => 'Phiếu tiếp nhận: ' . $data['receiving_code'], 'name' => 'phieu-tiep-nhan', 'icon' => 'po'];
        }
        if (isset($data['note']) && $data['note'] !== null) {
            $filters[] = ['value' => 'Ghi chú: ' . $data['note'], 'name' => 'ghi-chu', 'icon' => 'po'];
        }
        if (isset($data['customer']) && $data['customer'] !== null) {
            $filters[] = ['value' => 'Khách hàng: ' . count($data['customer']) . ' đã chọn', 'name' => 'khách hàng', 'icon' => 'user'];
        }
        if (isset($data['date']) && $data['date'][1] !== null) {
            $date_start = date("d/m/Y", strtotime($data['date'][0]));
            $date_end = date("d/m/Y", strtotime($data['date'][1]));
            $filters[] = ['value' => 'Ngày lập phiếu: từ ' . $date_start . ' đến ' . $date_end, 'name' => 'ngay-lap-phieu', 'icon' => 'date'];
        }
        if (isset($data['form_type']) && $data['form_type'] !== null) {
            $statusValues = [];
            if (in_array(1, $data['form_type'])) {
                $statusValues[] = '<span style="color: #858585;">Bảo hành</span>';
            }
            if (in_array(2, $data['form_type'])) {
                $statusValues[] = '<span style="color: #08AA36BF;">Dịch vụ</span>';
            }
            if (in_array(3, $data['form_type'])) {
                $statusValues[] = '<span style="color:rgba(67, 54, 154, 0.75);">Bảo hành dịch vụ</span>';
            }
            $filters[] = ['value' => 'Loại phiếu: ' . implode(', ', $statusValues), 'name' => 'loai-phieu', 'icon' => 'status'];
        }
        if (isset($data['status']) && $data['status'] !== null) {
            $statusValues = [];
            if (in_array(1, $data['status'])) {
                $statusValues[] = '<span style="color: #858585;">Hoàn thành</span>';
            }
            if (in_array(2, $data['status'])) {
                $statusValues[] = '<span style="color: #08AA36BF;">Khách không đồng ý</span>';
            }
            $filters[] = ['value' => 'Tình-trạng: ' . implode(', ', $statusValues), 'name' => 'tinh-trang', 'icon' => 'status'];
        }

        if ($request->ajax()) {
            $returnforms = $this->returnforms->getReturnFormAjax($data);
            return response()->json([
                'data' => $returnforms,
                'filters' => $filters,
            ]);
        }
        return false;
    }
}
