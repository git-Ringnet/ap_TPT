<?php

namespace App\Http\Controllers;

use App\Models\Customers;
use App\Models\Exports;
use App\Models\Product;
use App\Models\ProductExport;
use App\Models\SerialNumber;
use App\Models\User;
use App\Models\warrantyLookup;
use Illuminate\Http\Request;

class ExportsController extends Controller
{
    private $exports;

    public function __construct()
    {
        $this->exports = new Exports();
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $title = "Phiếu xuất hàng";
        $exports = Exports::with(['user', 'customer'])->get();
        $users = User::all();
        $customers = Customers::all();
        return view('expertise.export.index', compact('title', 'exports', 'users', 'customers'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $title = "Tạo phiếu xuất hàng";
        $export_code = $this->exports->generateExportCode();
        $users = User::all();
        $customers = Customers::all();
        $products = Product::all();
        return view('expertise.export.create', compact('title', 'export_code', 'users', 'customers', 'products'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $export_id = $this->exports->addExport($request->all());
        $dataTest = $request->input('data-test');

        // Giải mã chuỗi JSON thành mảng
        $uniqueProductsArray = json_decode($dataTest, true);

        // Duyệt qua từng sản phẩm trong mảng
        foreach ($uniqueProductsArray as $serial) {
            if (isset($serial['serial']) && !empty($serial['serial'])) {
                $sn = SerialNumber::where("serial_code", $serial['serial'])
                    ->first();
                if ($sn) {
                    $sn->update([
                        'status' => 2,
                    ]);
                    ProductExport::create([
                        'export_id' => $export_id,
                        'product_id' => $serial['product_id'],
                        'quantity' => 1,
                        'sn_id' => $sn->id,
                        'warranty' => $serial['warranty'],
                        'note' => $serial['note_seri'],
                    ]);
                    //Tra cứu bảo hành
                    warrantyLookup::create([
                        'product_id' => $serial['product_id'],
                        'sn_id' => $sn->id,
                        'customer_id' => $request->customer_id,
                        'export_return_date' => $request->date_create,
                        'warranty' => $serial['warranty'],
                        'status' => 0,
                        'warranty_expire_date' => date('Y-m-d', strtotime($request->date_create . ' + ' . $serial['warranty'] . ' months')),
                    ]);
                }
            }
        }
        return redirect()->route('exports.index')->with('msg', 'Tạo phiếu xuất hàng thành công!');;
    }

    /**
     * Display the specified resource.
     */
    public function show(String $id)
    {
        $export = Exports::with(['user', 'customer'])->where("exports.id", $id)->first();
        $title = "Xem chi tiết phiếu xuất hàng";
        $productExports = ProductExport::with(['export', 'product', 'serialNumber'])
            ->where("export_id", $id)
            ->get();
        return view('expertise.export.show', compact('title', 'export', 'productExports'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(String $id)
    {
        $export = Exports::with(['user', 'customer'])->where("exports.id", $id)->first();
        $users = User::all();
        $customers = Customers::all();
        $title = "Sửa phiếu xuất hàng";
        $productExports = ProductExport::where("export_id", $id)
            ->get()->groupBy('product_id');
        $productAll = Product::all();
        $exports = Exports::with(['user', 'customer'])->get();
        return view('expertise.export.edit', compact('title', 'export', 'users', 'customers', 'productExports', 'productAll', 'exports'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, String $id)
    {
        // Validate dữ liệu đầu vào
        $validatedData = $request->validate(
            [
                'export_code' => 'required|string|max:255|unique:exports,export_code,' . $id,
                'user_id'     => 'required|integer|exists:users,id',
                'phone'       => 'nullable|string|max:15',
                'date_create' => 'required|date',
                'customer_id' => 'required|integer|exists:customers,id',
                'address'     => 'nullable|string|max:255',
                'note'        => 'nullable|string|max:500',
            ],
            [
                'export_code.required' => 'Mã phiếu là bắt buộc.',
                'user_id.required'     => 'Người nhập là bắt buộc.',
                'date_create.required' => 'Ngày tạo là bắt buộc.',
            ]
        );
        $export = Exports::findOrFail($id);

        // Cập nhật dữ liệu
        $export->update($validatedData);

        // Lấy dữ liệu từ form gửi lên
        $dataTest = json_decode($request->input('data-test'), true);

        // Lấy danh sách serial từ data-test
        $formSerials = array_column($dataTest, 'serial');

        // Lấy serials từ database (chỉ những serial đang active hoặc hợp lệ)
        $existingSerials = SerialNumber::whereIn('serial_code', $formSerials)->get();
        $existingSerialCodes = $existingSerials->pluck('serial_code')->toArray();
        $serialIds = $existingSerials->pluck('id', 'serial_code')->toArray(); // Map serial_code -> sn_id

        // Lấy danh sách sn_id hiện tại trong ProductExport
        $currentSnIds = ProductExport::where('export_id', $id)->pluck('sn_id')->toArray();

        // 1. Xử lý thêm mới hoặc cập nhật các serial có sẵn
        foreach ($dataTest as $data) {
            if (isset($data['serial'], $data['product_id']) && !empty($data['serial']) && isset($serialIds[$data['serial']])) {
                $snId = $serialIds[$data['serial']];

                // Thêm hoặc cập nhật ProductExport
                $existingExport = ProductExport::where('export_id', $id)
                    ->where('product_id', $data['product_id'])
                    ->where('sn_id', $snId)
                    ->first();

                if (!$existingExport) {
                    ProductExport::create([
                        'export_id' => $id,
                        'product_id' => $data['product_id'],
                        'sn_id' => $snId,
                        'note' => $data['note_seri'] ?? '',
                        'warranty' => $data['warranty'] ?? 12,
                    ]);
                } else {
                    // Cập nhật ghi chú nếu cần
                    $existingExport->update([
                        'warranty' => $data['warranty'] ?? 12,
                        'note' => $data['note_seri'] ?? '',
                    ]);
                }

                // Cập nhật trạng thái serial thành 2 (exported)
                SerialNumber::where('id', $snId)->update(['status' => 2]);

                // **Thêm mới vào WarrantyLookup nếu chưa tồn tại**
                $existingWarranty = WarrantyLookup::where('sn_id', $snId)->first();
                if (!$existingWarranty) {
                    WarrantyLookup::create([
                        'product_id' => $data['product_id'],
                        'sn_id' => $snId,
                        'customer_id' => $request->customer_id,
                        'export_return_date' => $request->date_create,
                        'warranty' => $data['warranty'] ?? 12,
                        'status' => 0,
                        'warranty_expire_date' => date('Y-m-d', strtotime($request->date_create . ' + ' . $data['warranty'] . ' months')),
                    ]);
                } else {
                    // Cập nhật ghi chú nếu cần
                    $existingWarranty->update([
                        'warranty' => $data['warranty'] ?? 12,
                        'warranty_expire_date' => date('Y-m-d', strtotime($request->date_create . ' + ' . $data['warranty'] . ' months')),
                    ]);
                }
            }
        }

        // 2. Lấy danh sách sn_id từ form (chỉ những serial tồn tại)
        $formSnIds = array_filter(array_map(function ($data) use ($serialIds) {
            return isset($data['serial']) ? $serialIds[$data['serial']] ?? null : null;
        }, $dataTest));

        // 3. Xử lý các serial bị xóa khỏi phiếu xuất
        $formSnIds = array_filter($formSnIds); // Chỉ lấy những id hợp lệ
        $removedSnIds = array_diff($currentSnIds, $formSnIds);

        // Cập nhật trạng thái serial bị xóa về 1 (active)
        if (!empty($removedSnIds)) {
            SerialNumber::whereIn('id', $removedSnIds)->update(['status' => 1]);

            // **Xóa khỏi WarrantyLookup nếu tồn tại**
            WarrantyLookup::whereIn('sn_id', $removedSnIds)->delete();
        }

        // Xóa serials bị xóa khỏi ProductExport
        ProductExport::where('export_id', $id)
            ->whereIn('sn_id', $removedSnIds)
            ->delete();

        return redirect()->route('exports.index')->with('msg', 'Cập nhật thành công phiếu xuất hàng!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(String $id)
    {
        $export = Exports::findOrFail($id);
        ProductExport::where('export_id', $id)->delete();
        $export->delete();
        return redirect()->route('exports.index')->with('msg', 'Xóa thành công phiếu xuất hàng!');
    }
    public function filterData(Request $request)
    {
        $data = $request->all();
        $filters = [];
        if (isset($data['ma']) && $data['ma'] !== null) {
            $filters[] = ['value' => 'Mã: ' . $data['ma'], 'name' => 'ma-phieu', 'icon' => 'po'];
        }
        if (isset($data['note']) && $data['note'] !== null) {
            $filters[] = ['value' => 'Ghi chú: ' . $data['note'], 'name' => 'ghi-chu', 'icon' => 'po'];
        }
        if (isset($data['user']) && $data['user'] !== null) {
            $filters[] = ['value' => 'Người lập phiếu: ' . count($data['user']) . ' đã chọn', 'name' => 'nguoi-lap-phieu', 'icon' => 'user'];
        }
        if (isset($data['customer']) && $data['customer'] !== null) {
            $filters[] = ['value' => 'Khách hàng: ' . count($data['customer']) . ' đã chọn', 'name' => 'khách hàng', 'icon' => 'user'];
        }
        if (isset($data['date']) && $data['date'][1] !== null) {
            $date_start = date("d/m/Y", strtotime($data['date'][0]));
            $date_end = date("d/m/Y", strtotime($data['date'][1]));
            $filters[] = ['value' => 'Ngày lập phiếu: từ ' . $date_start . ' đến ' . $date_end, 'name' => 'ngay-lap-phieu', 'icon' => 'date'];
        }
        if ($request->ajax()) {
            $exports = $this->exports->getExportAjax($data);
            return response()->json([
                'data' => $exports,
                'filters' => $filters,
            ]);
        }
        return false;
    }
}
