<?php

namespace App\Http\Controllers;

use App\Models\Imports;
use App\Models\Product;
use App\Models\Providers;
use App\Models\SerialNumber;
use Illuminate\Http\Request;

class ImportsController extends Controller
{
    private $imports;

    public function __construct()
    {
        $this->imports = new Imports();
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $title = "Phiếu nhập hàng";
        $imports = $this->imports->getAllImports();
        return view('expertise.import.index', compact('title', 'imports'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $title = "Tạo phiếu nhập hàng";
        $providers = Providers::all();
        $import_code = $this->imports->generateImportCode();
        //Lấy data products
        $products = Product::all();
        return view('expertise.import.create', compact('title', 'providers', 'import_code', "products"));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // $validatedData = $request->validate(
        //     [
        //         'import_code' => 'required|string|max:255|unique:imports,import_code',
        //         'user_id'     => 'required|integer|exists:users,id',
        //         'phone'       => 'nullable|string|max:15',
        //         'date_create' => 'required|date',
        //         'provider_id' => 'required|integer|exists:providers,id',
        //         'address'     => 'nullable|string|max:255',
        //         'note'        => 'nullable|string|max:500',
        //     ],
        //     [
        //         'import_code.required' => 'Mã nhập kho là bắt buộc.',
        //         'import_code.unique'   => 'Mã nhập kho đã tồn tại.',
        //         'user_id.required'     => 'Người nhập là bắt buộc.',
        //         'date_create.required' => 'Ngày tạo là bắt buộc.',
        //         'provider_id.required'     => 'Nhà cung cấp là bắt buộc.',
        //     ]
        // );

        // $import = Imports::create($validatedData);
        // dd($request->all());
        $dataTest = $request->input('data-test');

        // Giải mã chuỗi JSON thành mảng
        $uniqueProductsArray = json_decode($dataTest, true);

        // Duyệt qua từng sản phẩm trong mảng
        foreach ($uniqueProductsArray as $productData) {
            // Lưu vào bảng 'products'
            $product = Product::create([
                'product_code' => $productData['productCode'],
                'product_name' => $productData['productName'],
                'brand' => $productData['brand'],
                'quantity' => $productData['quantity'],
            ]);

            // Duyệt qua các số serial của sản phẩm và lưu vào bảng 'serial_numbers'
            foreach ($productData['serial'] as $index => $serial) {
                SerialNumber::create([
                    'serial_code' => $serial,
                    'product_id' => $product->id,  // Liên kết với sản phẩm vừa lưu
                    'note' => $productData['note_seri'][$index] ?? '',  // Nếu không có ghi chú, mặc định là rỗng
                ]);
            }
        }

        $this->imports->addImport($request->all());
        return redirect()->route('imports.index')->with('msg', 'Tạo phiếu nhập hàng thành công!');;
    }

    /**
     * Display the specified resource.
     */
    public function show(String $id)
    {
        $import = Imports::findOrFail($id);
        $title = "Xem chi tiết phiếu nhập hàng";
        return view('expertise.import.see', compact('title', 'import'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(String $id)
    {
        $import = Imports::findOrFail($id);
        $title = "Sửa phiếu nhập hàng";
        return view('expertise.import.edit', compact('title', 'import'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, String $id)
    {
        // Validate dữ liệu đầu vào
        $validatedData = $request->validate(
            [
                'import_code' => 'required|string|max:255|unique:imports,import_code,' . $id,
                'user_id'     => 'required|integer|exists:users,id',
                'phone'       => 'nullable|string|max:15',
                'date_create' => 'required|date',
                'provider_id' => 'required|integer|exists:providers,id',
                'address'     => 'nullable|string|max:255',
                'note'        => 'nullable|string|max:500',
            ],
            [
                'import_code.required' => 'Mã nhập kho là bắt buộc.',
                'user_id.required'     => 'Người nhập là bắt buộc.',
                'date_create.required' => 'Ngày tạo là bắt buộc.',
            ]
        );

        // Tìm import theo ID
        $import = Imports::findOrFail($id);

        // Cập nhật dữ liệu
        $import->update($validatedData);

        return redirect()->route('imports.index')->with('msg', 'Cập nhật thành công phiếu nhập hàng!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(String $id)
    {
        $import = Imports::findOrFail($id);
        $import->delete();
        return redirect()->route('imports.index')->with('msg', 'Xóa thành công phiếu nhập hàng!');
    }
}
