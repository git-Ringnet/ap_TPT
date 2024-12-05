<?php

namespace App\Http\Controllers;

use App\Models\Groups;
use App\Models\Warehouse;
use Illuminate\Http\Request;

class WarehouseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $warehouses = Warehouse::all();
        $title = 'Kho';
        return view('setup.warehouses.index', compact('warehouses', 'title'));
    }

    public function create()
    {
        $title = 'Thêm kho';
        return view('setup.warehouses.create', compact('title'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'type' => 'nullable|integer',
            'warehouse_code' => 'required|string|max:255|unique:warehouses,warehouse_code',
            'warehouse_name' => 'nullable|string|max:255',
            'address' => 'nullable|string',
        ]);

        Warehouse::create($validated);

        return redirect()->route('warehouses.index')->with('success', 'Warehouse created successfully.');
    }

    public function show(Warehouse $warehouse)
    {
        return view('warehouses.show', compact('warehouse'));
    }

    public function edit(Warehouse $warehouse)
    {
        $title = 'Sửa thông tin kho';
        return view('setup.warehouses.edit', compact('warehouse', 'title'));
    }

    public function update(Request $request, Warehouse $warehouse)
    {
        $validated = $request->validate([
            'type' => 'nullable|integer',
            'warehouse_code' => 'required|string|max:255|unique:warehouses,warehouse_code,' . $warehouse->id,
            'warehouse_name' => 'nullable|string|max:255',
            'address' => 'nullable|string',
        ]);

        $warehouse->update($validated);

        return redirect()->route('warehouses.index')->with('success', 'Warehouse updated successfully.');
    }

    public function destroy(Warehouse $warehouse)
    {
        $warehouse->delete();

        return redirect()->route('warehouses.index')->with('success', 'Warehouse deleted successfully.');
    }
}
