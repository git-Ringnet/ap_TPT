<?php

namespace App\Http\Controllers;

use App\Models\warrantyLookup;
use Illuminate\Http\Request;

class WarrantyLookupController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $title = "Tra cứu bảo hành";
        $warranty = warrantyLookup::with(['product', 'serialNumber', 'customer'])->get();
        return view('expertise.warrantyLookup.index', compact('title', 'warranty'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
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
    public function show(warrantyLookup $warrantyLookup)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(String $id)
    {
        $title = "Tra cứu bảo hành";
        $warrantyLookup = warrantyLookup::with(['product'])
            ->where("id", $id)->first();
        return view('expertise.warrantyLookup.edit', compact('title','warrantyLookup'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, warrantyLookup $warrantyLookup)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(warrantyLookup $warrantyLookup)
    {
        //
    }
}
