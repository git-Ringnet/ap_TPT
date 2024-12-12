<?php

namespace App\Http\Controllers;

use App\Models\Customers;
use App\Models\Exports;
use App\Models\Product;
use App\Models\ProductExport;
use App\Models\SerialNumber;
use App\Models\User;
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
        return view('expertise.export.index', compact('title', 'exports'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $title = "Tạo phiếu xuất hàng";
        $export_code = $this->exports->generateExportCode();
        $users = User::all();
        $cumtomers = Customers::all();
        $products = Product::all();
        return view('expertise.export.create', compact('title', 'export_code', 'users', 'cumtomers', 'products'));
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
                $sn_id = SerialNumber::where("serial_code", $serial['serial'])
                    ->value("id");
                if ($sn_id) {
                    ProductExport::create([
                        'export_id' => $export_id,
                        'product_id' => $serial['product_id'],
                        'quantity' => 1,
                        'sn_id' => $sn_id,
                        'warranty' => $serial['warranty'],
                        'note' => $serial['note_seri'],
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
        $cumtomers = Customers::all();
        $title = "Sửa phiếu xuất hàng";
        $productExports = ProductExport::with(['export', 'product', 'serialNumber'])
            ->where("export_id", $id)
            ->get();
        $products = Product::all();
        return view('expertise.export.edit', compact('title', 'export', 'users', 'cumtomers', 'productExports', 'products'));
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

        return redirect()->route('exports.index')->with('msg', 'Cập nhật thành công phiếu xuất hàng!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(String $id)
    {
        $export = Exports::findOrFail($id);
        $export->delete();
        return redirect()->route('exports.index')->with('msg', 'Xóa thành công phiếu xuất hàng!');
    }
}
