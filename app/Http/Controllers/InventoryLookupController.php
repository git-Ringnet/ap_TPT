<?php

namespace App\Http\Controllers;

use App\Models\InventoryHistory;
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
    public function edit(String $id)
    {
        $title = "Tra cứu tồn kho";
        $inventoryLookup = InventoryLookup::with(['product', 'serialNumber'])
            ->where("id", $id)
            ->first();
        $histories = InventoryHistory::with('inventoryLookup')
            ->where("inventory_lookup_id", $id)
            ->get();
        return view('expertise.inventoryLookup.edit', compact('title', 'inventoryLookup', 'histories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(String $id, Request $request)
    {
        $inventoryLookup = InventoryLookup::findOrFail($id);
        $data = $request->only([
            'warranty_date',
            'note',
        ]);
        $inventoryLookup->update($data);
        //Lưu lịch sử 
        if (!empty($data['warranty_date']) && !empty($data['note'])) {
            // Thêm mới vào inventory_history
            InventoryHistory::create([
                'inventory_lookup_id' => $id,
                'import_date' => $inventoryLookup->import_date,
                'storage_duration' => $inventoryLookup->storage_duration,
                'warranty_date' => $request->warranty_date,
                'note' => $request->note,
            ]);
        }
        return redirect()->route('inventoryLookup.index')->with('msg', 'Cập nhật thành công bảo trì định kỳ!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(InventoryLookup $inventoryLookup)
    {
        //
    }
}
