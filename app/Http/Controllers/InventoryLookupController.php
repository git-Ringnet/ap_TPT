<?php

namespace App\Http\Controllers;

use App\Models\InventoryLookup;
use Illuminate\Http\Request;

class InventoryLookupController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $title = "Tra cứu tồn kho";
        $inventory = InventoryLookup::with(['product', 'serialNumber', 'provider'])->get();
        return view('expertise.inventoryLookup.index', compact('title', 'inventory'));
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
    public function show(InventoryLookup $inventoryLookup)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $title = "Tra cứu tồn kho";
        return view('expertise.inventoryLookup.edit', compact('title'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, InventoryLookup $inventoryLookup)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(InventoryLookup $inventoryLookup)
    {
        //
    }
}
