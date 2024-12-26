<?php

namespace App\Http\Controllers;

use App\Models\Groups;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    private $products;
    public function __construct()
    {
        $this->products = new Product();
    }
    public function index()
    {
        $products = Product::all();
        $title = 'Hàng hoá';
        $groups = Groups::where('group_type_id', 3)->get();
        return view('setup.products.index', compact('products', 'title', 'groups'));
    }

    // Show the form for creating a new product
    public function create()
    {
        $title = 'Tạo mới hàng hoá';
        $groups = Groups::where('group_type_id', 3)->get();
        return view('setup.products.create', compact('title', 'groups'));
    }

    // Store a newly created product in storage
    public function store(Request $request)
    {
        $request->validate([
            'product_code' => 'required|string|unique:products',
            'product_name' => 'nullable|string',
            'brand' => 'nullable|string',
            'warranty' => 'nullable|integer',
        ]);

        Product::create([
            'group_id' => $request->input('group_id'),
            'product_code' => $request->input('product_code'),
            'product_name' => $request->input('product_name'),
            'brand' => $request->input('brand'),
            'warranty' => $request->input('warranty'),
        ]);

        return redirect()->route('products.index')->with('success', 'Product created successfully.');
    }

    // Show the form for editing the specified product
    public function edit(Product $product)
    {
        $title = 'Chỉnh sửa hàng hoá';
        $groups = Groups::where('group_type_id', 3)->get();
        return view('setup.products.edit', compact('product', 'title', 'groups'));
    }

    // Update the specified product in storage
    public function update(Request $request, Product $product)
    {
        $request->validate([
            'product_code' => 'required|string|unique:products,product_code,' . $product->id,
            'product_name' => 'nullable|string',
            'brand' => 'nullable|string',
            'warranty' => 'nullable|integer',
        ]);

        $product->update([
            'group_id' => $request->input('group_id'),
            'product_code' => $request->input('product_code'),
            'product_name' => $request->input('product_name'),
            'brand' => $request->input('brand'),
            'warranty' => $request->input('warranty'),
        ]);

        return redirect()->route('products.index')->with('success', 'Product updated successfully.');
    }

    // Remove the specified product from storage
    public function destroy(Product $product)
    {
        $product->delete();

        return redirect()->route('products.index')->with('msg', 'Product deleted successfully.');
    }
    public function filterData(Request $request)
    {
        $data = $request->all();
        $filters = [];
        if (isset($data['ma']) && $data['ma'] !== null) {
            $filters[] = ['value' => 'Mã: ' . $data['ma'], 'name' => 'ma', 'icon' => 'po'];
        }
        if (isset($data['ten']) && $data['ten'] !== null) {
            $filters[] = ['value' => 'Tên: ' . $data['ten'], 'name' => 'ten', 'icon' => 'po'];
        }
        if (isset($data['hang']) && $data['hang'] !== null) {
            $filters[] = ['value' => 'Hãng: ' . $data['hang'], 'name' => 'hang', 'icon' => 'po'];
        }
        if (isset($data['bao_hanh']) && $data['bao_hanh'][1] !== null) {
            $filters[] = ['value' => 'Bảo hành: ' . $data['bao_hanh'][0] . ' ' . $data['bao_hanh'][1], 'name' => 'bao-hanh', 'icon' => 'money'];
        }
        if ($request->ajax()) {
            $products = $this->products->getAllProducts($data);
            return response()->json([
                'data' => $products,
                'filters' => $filters,
            ]);
        }
        return false;
    }
}
