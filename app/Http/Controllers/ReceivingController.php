<?php

namespace App\Http\Controllers;

use App\Models\Receiving;
use Illuminate\Http\Request;

class ReceivingController extends Controller
{
    // Display a listing of the receiving records
    public function index()
    {
        $receivings = Receiving::all();
        return view('expertise.receivings.index', compact('receivings'));
    }

    // Show the form for creating a new receiving record
    public function create()
    {
        $title = 'Tạo phiếu tiếp nhận';

        return view('expertise.receivings.create', compact('title'));
    }

    // Store a newly created receiving record in storage
    public function store(Request $request)
    {
        $validated = $request->validate([
            'branch_id' => 'required|array|min:1',
            'branch_id.*' => 'in:1,2',
            'form_type' => 'required|array|min:1',
            'form_type.*' => 'in:1,2,3',
            'form_code' => 'required|string|unique:receiving,form_code',
            'customer_id' => 'required|integer',
            'address' => 'required|string',
            'date_created' => 'required|date',
            'contact_person' => 'nullable|string',
            'notes' => 'nullable|string',
            'user_id' => 'required|integer',
            'phone' => 'nullable|string',
            'closed_at' => 'nullable|date',
            'status' => 'required|integer',
            'state' => 'required|integer',
        ]);
        Receiving::create($validated);

        return redirect()->route('receivings.index')->with('success', 'Receiving record created successfully.');
    }

    // Display the specified receiving record
    public function show(Receiving $receiving)
    {
        return view('expertise.receivings.show', compact('receiving'));
    }

    // Show the form for editing the specified receiving record
    public function edit(Receiving $receiving)
    {
        return view('expertise.receivings.edit', compact('receiving'));
    }

    // Update the specified receiving record in storage
    public function update(Request $request, Receiving $receiving)
    {
        $validated = $request->validate([
            'branch_id' => 'required|integer',
            'form_type' => 'required|integer',
            'form_code' => 'required|string|unique:receiving,form_code,' . $receiving->id,
            'customer_id' => 'required|integer',
            'address' => 'required|string',
            'date_created' => 'required|date',
            'contact_person' => 'nullable|string',
            'notes' => 'nullable|string',
            'user_id' => 'required|integer',
            'phone' => 'nullable|string',
            'closed_at' => 'nullable|date',
            'status' => 'required|integer',
            'state' => 'required|integer',
        ]);

        $receiving->update($validated);

        return redirect()->route('receivings.index')->with('success', 'Receiving record updated successfully.');
    }

    // Remove the specified receiving record from storage
    public function destroy(Receiving $receiving)
    {
        $receiving->delete();

        return redirect()->route('receivings.index')->with('success', 'Receiving record deleted successfully.');
    }
}
