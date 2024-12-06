<?php

namespace App\Http\Controllers;

use App\Models\Imports;
use App\Models\Providers;
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
        return view('expertise.import.create', compact('title', 'providers', 'import_code'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
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
    public function edit(Imports $imports)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Imports $imports)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Imports $imports)
    {
        //
    }
}
