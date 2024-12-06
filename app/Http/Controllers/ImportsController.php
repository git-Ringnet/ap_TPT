<?php

namespace App\Http\Controllers;

use App\Models\Imports;
use Illuminate\Http\Request;

class ImportsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $title = "Phiếu nhập hàng";
        return view('expertise.import.index', compact('title'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $title = "Tạo phiếu nhập hàng";
        return view('expertise.import.create', compact('title'));
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
    public function show(Imports $imports)
    {
        //
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
