<?php

namespace App\Http\Controllers;

use App\Models\Customers;
use App\Models\Product;
use App\Models\ReceivedProduct;
use App\Models\Receiving;
use App\Models\ReturnForm;
use App\Models\SerialNumber;
use App\Models\User;
use Illuminate\Http\Request;

class ReceivingController extends Controller
{
    // Display a listing of the receiving records

    private $receivings;

    public function __construct()
    {
        $this->receivings = new Receiving();
    }

    public function index()
    {
        $receivings = Receiving::all();
        $title = 'Phiếu tiếp nhận';
        $customers = Customers::all();
        return view('expertise.receivings.index', compact('receivings', 'title', 'customers'));
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
    public function filterData(Request $request)
    {
        $data = $request->all();
        $filters = [];
        if (isset($data['ma']) && $data['ma'] !== null) {
            $filters[] = ['value' => 'Mã phiếu: ' . $data['ma'], 'name' => 'ma-phieu', 'icon' => 'po'];
        }
        if (isset($data['note']) && $data['note'] !== null) {
            $filters[] = ['value' => 'Ghi chú: ' . $data['note'], 'name' => 'ghi-chu', 'icon' => 'po'];
        }
        if (isset($data['customer']) && $data['customer'] !== null) {
            $filters[] = ['value' => 'Khách hàng: ' . count($data['customer']) . ' đã chọn', 'name' => 'khách hàng', 'icon' => 'user'];
        }
        if (isset($data['date']) && $data['date'][1] !== null) {
            $date_start = date("d/m/Y", strtotime($data['date'][0]));
            $date_end = date("d/m/Y", strtotime($data['date'][1]));
            $filters[] = ['value' => 'Ngày lập phiếu: từ ' . $date_start . ' đến ' . $date_end, 'name' => 'ngay-lap-phieu', 'icon' => 'date'];
        }
        if (isset($data['closed_at']) && $data['closed_at'][1] !== null) {
            $date_start = date("d/m/Y", strtotime($data['closed_at'][0]));
            $date_end = date("d/m/Y", strtotime($data['closed_at'][1]));
            $filters[] = ['value' => 'Ngày đóng phiếu: từ ' . $date_start . ' đến ' . $date_end, 'name' => 'ngay-dong-phieu', 'icon' => 'date'];
        }
        // Hàm hỗ trợ tạo filter từ mảng trạng thái
        function generateStatusFilter($data, $key, $statusMapping, $label, $name)
        {
            if (isset($data[$key]) && $data[$key] !== null) {
                $statusValues = [];
                foreach ($data[$key] as $status) {
                    if (isset($statusMapping[$status])) {
                        $statusValues[] = '<span style="color: ' . $statusMapping[$status]['color'] . ';">' . $statusMapping[$status]['label'] . '</span>';
                    }
                }
                if (!empty($statusValues)) {
                    return ['value' => $label . ': ' . implode(', ', $statusValues), 'name' => $name, 'icon' => 'status'];
                }
            }
            return null;
        }
        // Loại phiếu
        $formTypeMapping = [
            1 => ['label' => 'Bảo hành', 'color' => '#858585'],
            2 => ['label' => 'Dịch vụ', 'color' => '#08AA36BF'],
            3 => ['label' => 'Bảo hành dịch vụ', 'color' => '#08AA36BF'],
        ];
        $formTypeFilter = generateStatusFilter($data, 'form_type', $formTypeMapping, 'Loại phiếu', 'loai-phieu');
        if ($formTypeFilter) {
            $filters[] = $formTypeFilter;
        }
        // Hàng tiếp nhận
        $brandTypeMapping = [
            1 => ['label' => 'Nội bộ', 'color' => '#858585'],
            2 => ['label' => 'Bên ngoài', 'color' => '#08AA36BF'],
        ];
        $brandTypeFilter = generateStatusFilter($data, 'brand_type', $brandTypeMapping, 'Trạng thái', 'trang-thai');
        if ($brandTypeFilter) {
            $filters[] = $brandTypeFilter;
        }
        // Tình trạng
        $statusMapping = [
            1 => ['label' => 'Tiếp nhận', 'color' => '#858585'],
            2 => ['label' => 'Xử lý', 'color' => '#08AA36BF'],
            3 => ['label' => 'Hoàn thành', 'color' => '#08AA36BF'],
            4 => ['label' => 'Khách không đồng ý', 'color' => '#08AA36BF'],
        ];
        $statusFilter = generateStatusFilter($data, 'status', $statusMapping, 'Tình trạng', 'tinh-trang');
        if ($statusFilter) {
            $filters[] = $statusFilter;
        }
        // Trạng thái
        $stateMapping = [
            1 => ['label' => 'Quá hạn', 'color' => '#858585'],
            2 => ['label' => 'Chưa xử lý', 'color' => '#08AA36BF'],
            0 => ['label' => 'Blank', 'color' => '#08AA36BF'],
        ];
        $stateFilter = generateStatusFilter($data, 'state', $stateMapping, 'Trạng thái', 'trang-thai');
        if ($stateFilter) {
            $filters[] = $stateFilter;
        }

        if ($request->ajax()) {
            $receivings = $this->receivings->getReceiAjax($data);
            return response()->json([
                'data' => $receivings,
                'filters' => $filters,
            ]);
        }
        return false;
    }
    public function updateStatus(Request $request)
    {
        $status = $request->input('status');
        $recei = $request->input('recei');
        $returndata = $request->input('returndata');
        try {
            // Cập nhật Receiving
            if ($recei) {
                $receiving = Receiving::findOrFail($recei);
                $receiving->status = $status;
                $receiving->save();
            }
            // Cập nhật ReturnForm nếu tồn tại
            if ($returndata) {
                $returnForm = ReturnForm::findOrFail($returndata);
                // Điều chỉnh status theo logic yêu cầu
                if ($status == 3) {
                    $returnForm->status = 1;
                } elseif ($status == 4) {
                    $returnForm->status = 2;
                } else {
                    $returnForm->status = $status; // Giữ nguyên nếu không thuộc 3 hoặc 4
                }

                $returnForm->save();
            }
            return response()->json(['status' => 'success', 'message' => 'Cập nhật trạng thái thành công']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }
}
