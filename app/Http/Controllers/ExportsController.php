<?php

namespace App\Http\Controllers;

use App\Models\Customers;
use App\Models\Exports;
use App\Models\Product;
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
        $exports = "";
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
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Exports $exports)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Exports $exports)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Exports $exports)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Exports $exports)
    {
        //
    }
}
