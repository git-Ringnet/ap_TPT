<?php

namespace App\Http\Controllers;

use App\Models\Quotation;
use App\Models\Receiving;
use Illuminate\Http\Request;

class QuotationController extends Controller
{
    // Hiển thị danh sách báo giá
    public function index()
    {
        $quotations = Quotation::paginate(10); // Lấy danh sách báo giá, phân trang
        $title = 'Danh sách báo giá';
        return view('expertise.quotations.index', compact('quotations', 'title'));
    }

    // Hiển thị form tạo mới báo giá
    public function create()
    {
        $quoteNumber = (new Receiving)->getQuoteCount('PBG', Quotation::class, 'quotation_code');
        $title = 'Tạo phiếu báo giá';
        return view('expertise.quotations.create', compact('title', 'quoteNumber'));
    }

    // Lưu báo giá mới vào cơ sở dữ liệu
    public function store(Request $request)
    {
        $validated = $request->validate([
            'reception_id' => 'required|integer',
            'quotation_code' => 'required|string|unique:quotations,quotation_code',
            'customer_id' => 'required|integer',
            'address' => 'required|string',
            'quotation_date' => 'required|date',
            'contact_person' => 'nullable|string',
            'notes' => 'nullable|string',
            'user_id' => 'required|integer',
            'contact_phone' => 'nullable|string',
            'total_amount' => 'required|numeric|min:0',
        ]);

        Quotation::create($validated);

        return redirect()->route('quotations.index')->with('msg', 'Tạo báo giá thành công!');
    }

    // Hiển thị chi tiết báo giá
    public function show(Quotation $quotation)
    {
        return view('expertise.quotations.show', compact('quotation'));
    }

    // Hiển thị form chỉnh sửa báo giá
    public function edit(Quotation $quotation)
    {
        return view('expertise.quotations.edit', compact('quotation'));
    }

    // Cập nhật thông tin báo giá
    public function update(Request $request, Quotation $quotation)
    {
        $validated = $request->validate([
            'reception_id' => 'required|integer',
            'quotation_code' => 'required|string|unique:quotations,quotation_code,' . $quotation->id,
            'customer_id' => 'required|integer',
            'address' => 'required|string',
            'quotation_date' => 'required|date',
            'contact_person' => 'nullable|string',
            'notes' => 'nullable|string',
            'user_id' => 'required|integer',
            'contact_phone' => 'nullable|string',
            'total_amount' => 'required|numeric|min:0',
        ]);

        $quotation->update($validated);

        return redirect()->route('quotations.index')->with('msg', 'Cập nhật báo giá thành công!');
    }

    // Xóa báo giá
    public function destroy(Quotation $quotation)
    {
        $quotation->delete();

        return redirect()->route('quotations.index')->with('msg', 'Xóa báo giá thành công!');
    }
}
