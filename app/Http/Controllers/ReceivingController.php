<?php

namespace App\Http\Controllers;

use App\Models\Customers;
use App\Models\Product;
use App\Models\ReceivedProduct;
use App\Models\Receiving;
use App\Models\SerialNumber;
use App\Models\User;
use Illuminate\Http\Request;

class ReceivingController extends Controller
{
    // Display a listing of the receiving records

    public function index()
    {
        $receivings = Receiving::all();
        $title = 'Phiếu tiếp nhận';
        return view('expertise.receivings.index', compact('receivings', 'title'));
    }

    // Show the form for creating a new receiving record
    public function create()
    {
        $title = 'Tạo phiếu tiếp nhận';
        $products = Product::all();
        $quoteNumber = (new Receiving)->getQuoteCount('PTN', Receiving::class, 'form_code_receiving');
        $customers = Customers::all();
        return view('expertise.receivings.create', compact('title', 'products', 'quoteNumber', 'customers'));
    }

    // Store a newly created receiving record in storage
    public function store(Request $request)
    {
        // dd($request->all());
        $validated = $request->validate([
            'branch_id' => 'required|min:1',
            'branch_id.*' => 'in:1,2',
            'form_type' => 'required|min:1',
            'form_type.*' => 'in:1,2,3',
            'form_code_receiving' => 'required|string|unique:receiving,form_code_receiving',
            'customer_id' => 'required|integer',
            'address' => 'nullable|string',
            'date_created' => 'required|date',
            'contact_person' => 'nullable|string',
            'notes' => 'nullable|string',
            'user_id' => 'required|integer',
            'phone' => 'nullable|string',
            'closed_at' => 'nullable|date',
            'status' => 'nullable|integer',
            'state' => 'nullable|integer',
        ]);
        // Tạo phiếu tiếp nhận
        $receiving = Receiving::create($validated);

        // Lấy dữ liệu 'data-test' từ request và giải mã JSON
        $dataTest = $request->input('data-test');
        $data = json_decode($dataTest, true);
        // Kiểm tra nếu dữ liệu không hợp lệ
        if (json_last_error() !== JSON_ERROR_NONE) {
            return back()->withErrors(['data-test' => 'Dữ liệu không hợp lệ']);
        }
        // Duyệt qua mảng và lưu vào bảng received_products
        foreach ($data as $item) {
            // Tìm hoặc tạo serial
            $serial = SerialNumber::firstOrCreate(
                ['serial_code' => $item['serial']],
                [
                    'product_id' => $item['product_id'],
                    'status' => 3,
                ]
            );
            // Đảm bảo status của serial luôn là 3
            if ($serial->status !== 3) {
                $serial->update(['status' => 3]);
            }
            // Lưu vào bảng received_products
            ReceivedProduct::create([
                'reception_id' => $receiving->id, // Lấy ID của phiếu tiếp nhận vừa tạo
                'product_id' => $item['product_id'], // ID hàng hóa
                'quantity' => 1, // Số lượng (Giả sử mỗi serial là 1 sản phẩm)
                'serial_id' => $serial->id, // Serial
                'note' => $item['note_seri'] ?? '', // Ghi chú
                'status' => $item['status_recept'] ?? '', // Tình trạng tiếp nhận
            ]);
        }
        // Chuyển hướng về danh sách phiếu tiếp nhận với thông báo thành công
        return redirect()->route('receivings.index')->with('msg', 'Tạo phiếu tiếp nhận thành công.');
    }


    // Display the specified receiving record
    public function show(Receiving $receiving)
    {
        return view('expertise.receivings.show', compact('receiving'));
    }

    // Show the form for editing the specified receiving record
    public function edit(Receiving $receiving)
    {
        $title = 'Chi tiết phiếu tiếp nhận';
        $products_all = Product::all();
        $customers = Customers::all();
        $receivedProducts = ReceivedProduct::where('reception_id', $receiving->id)
            ->get()
            ->groupBy('product_id');
        $users = User::all();
        $data = Receiving::all();
        return view('expertise.receivings.edit', compact('receiving', 'receivedProducts', 'products_all', 'customers', 'title', 'users', 'data'));
    }

    // Update the specified receiving record in storage
    public function update(Request $request, Receiving $receiving)
    {
        // Xác thực dữ liệu
        $validated = $request->validate([
            'branch_id' => 'required|integer',
            'form_type' => 'required|integer',
            'form_code_receiving' => 'required|string|unique:receiving,form_code_receiving,' . $receiving->id,
            'customer_id' => 'required|integer',
            'address' => 'nullable|string',
            'date_created' => 'required|date',
            'contact_person' => 'nullable|string',
            'notes' => 'nullable|string',
            'user_id' => 'required|integer',
            'phone' => 'nullable|string',
            'closed_at' => 'nullable|date',
            'status' => 'required|integer',
            'state' => 'nullable|integer',
        ]);

        // Cập nhật phiếu tiếp nhận
        $receiving->update($validated);

        // Lấy dữ liệu 'data-test' từ request và giải mã JSON
        $dataTest = $request->input('data-test');
        $data = json_decode($dataTest, true);

        // Kiểm tra nếu dữ liệu không hợp lệ
        if (json_last_error() !== JSON_ERROR_NONE) {
            return back()->withErrors(['data-test' => 'Dữ liệu không hợp lệ']);
        }

        // Lấy danh sách sản phẩm hiện tại trong bảng `received_products` liên quan đến `receiving`
        $existingProducts = ReceivedProduct::where('reception_id', $receiving->id)->get();

        // Mảng để theo dõi các serial đã xử lý
        $processedSerials = [];

        foreach ($data as $item) {
            // Tìm hoặc tạo serial
            $serial = SerialNumber::firstOrCreate(
                ['serial_code' => $item['serial']],
                [
                    'product_id' => $item['product_id'],
                    'status' => 3,
                ]
            );

            // Đảm bảo status của serial luôn là 3
            if ($serial->status !== 3) {
                $serial->update(['status' => 3]);
            }

            // Tìm sản phẩm đã tiếp nhận dựa trên serial_id
            $existingProduct = $existingProducts->firstWhere('serial_id', $serial->id);
            if ($existingProduct) {
                $existingProduct->update([
                    'product_id' => $item['product_id'],
                    'quantity' => 1,
                    'note' => $item['note_seri'] ?? '',
                    'status' => $item['status_recept'] ?? '',
                ]);
            } else {
                // Nếu sản phẩm không tồn tại, tạo mới
                ReceivedProduct::create([
                    'reception_id' => $receiving->id,
                    'product_id' => $item['product_id'],
                    'quantity' => 1,
                    'serial_id' => $serial->id,
                    'note' => $item['note_seri'] ?? '',
                    'status' => $item['status_recept'] ?? '',
                ]);
            }

            // Đánh dấu serial đã xử lý
            $processedSerials[] = $serial->id;
        }

        // Xóa các sản phẩm không có trong dữ liệu mới
        ReceivedProduct::where('reception_id', $receiving->id)
            ->whereNotIn('serial_id', $processedSerials)
            ->delete();

        // Chuyển hướng về danh sách phiếu tiếp nhận với thông báo thành công
        return redirect()->route('receivings.index')->with('msg', 'Cập nhật phiếu tiếp nhận thành công.');
    }




    // Remove the specified receiving record from storage
    public function destroy(Receiving $receiving)
    {
        $receiving->delete();

        return redirect()->route('receivings.index')->with('success', 'Receiving record deleted successfully.');
    }
    public function getReceiving(Request $request)
    {
        $receivingId = $request->selectedId;
        $receiving = Receiving::with('customer')->find($receivingId);
        $receivedProduct = ReceivedProduct::with('product', 'serial')->where('reception_id', $receivingId)->get();
        $products = Product::all();
        if ($receiving) {
            return response()->json([
                'success' => true,
                'data' => $receiving,
                'product' => $receivedProduct,
                'productData' => $products,
            ]);
        } else {
            return response()->json([
                'success' => false,
                'msg' => 'pha hoai khong a'
            ]);
        }
    }
}
