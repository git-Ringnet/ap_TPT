<?php

namespace App\Http\Controllers;

use App\Models\Exports;
use App\Models\Imports;
use App\Models\InventoryHistory;
use App\Models\InventoryLookup;
use App\Models\Product;
use App\Models\ProductImport;
use App\Models\Providers;
use App\Models\Quotation;
use App\Models\Receiving;
use App\Models\ReturnForm;
use App\Models\SerialNumber;
use App\Models\User;
use Carbon\Carbon;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
        $users = User::get();
        $providers = Providers::get();
        return view('expertise.import.index', compact('title', 'imports', 'users', 'providers'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $title = "Tạo phiếu nhập hàng";
        $providers = Providers::all();
        $import_code = $this->imports->generateImportCode();
        //Lấy data products
        $products = Product::all();
        //
        $users = User::all();
        return view('expertise.import.create', compact('title', 'providers', 'import_code', "products", "users"));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // $validatedData = $request->validate(
        //     [
        //         'import_code' => 'required|string|max:255|unique:imports,import_code',
        //         'user_id'     => 'required|integer|exists:users,id',
        //         'phone'       => 'nullable|string|max:15',
        //         'date_create' => 'required|date',
        //         'provider_id' => 'required|integer|exists:providers,id',
        //         'address'     => 'nullable|string|max:255',
        //         'note'        => 'nullable|string|max:500',
        //     ],
        //     [
        //         'import_code.required' => 'Mã nhập kho là bắt buộc.',
        //         'import_code.unique'   => 'Mã nhập kho đã tồn tại.',
        //         'user_id.required'     => 'Người nhập là bắt buộc.',
        //         'date_create.required' => 'Ngày tạo là bắt buộc.',
        //         'provider_id.required'     => 'Nhà cung cấp là bắt buộc.',
        //     ]
        // );

        // $import = Imports::create($validatedData);
        // dd($request->all());
        $import_id = $this->imports->addImport($request->all());
        $dataTest = $request->input('data-test');

        // Giải mã chuỗi JSON thành mảng
        $uniqueProductsArray = json_decode($dataTest, true);

        // Duyệt qua từng sản phẩm trong mảng
        foreach ($uniqueProductsArray as $serial) {
            if (isset($serial['serial']) && !empty($serial['serial'])) {
                $newSerial = SerialNumber::create([
                    'serial_code' => $serial['serial'],
                    'product_id' => $serial['product_id'],
                    'note' => $serial['note_seri'],
                ]);
                ProductImport::create([
                    'import_id' => $import_id,
                    'product_id' => $serial['product_id'],
                    'quantity' => 1,
                    'sn_id' => $newSerial->id,
                    'note' => $serial['note_seri'],
                ]);
                //Tra cứu tồn kho
                InventoryLookup::create([
                    'product_id' => $serial['product_id'],
                    'sn_id' => $newSerial->id,
                    'provider_id' => $request->provider_id,
                    'import_date' => $request->date_create,
                    'storage_duration' => 0,
                    'status' => 0,
                ]);

                $records = InventoryLookup::all();
                foreach ($records as $record) {
                    // Tính thời gian tồn kho
                    $receivedDate = Carbon::parse($record->import_date); // Ngày nhập kho
                    $storageDuration = $receivedDate->diffInDays(Carbon::now()); // Tính số ngày tồn kho

                    // Cập nhật thời gian tồn kho
                    $record->update(['storage_duration' => $storageDuration]);

                    // Kiểm tra lần bảo trì đầu tiên hoặc các lần sau
                    if ($storageDuration >= 90 && !$record->warranty_date) {
                        // Lần bảo trì đầu tiên
                        $record->status = 1;
                        $record->save();
                    } else if ($record->warranty_date) {
                        // Các lần bảo trì tiếp theo
                        $nextMaintenanceDate = Carbon::parse($record->warranty_date)->addDays(90);

                        if (Carbon::now()->greaterThanOrEqualTo($nextMaintenanceDate)) {
                            // Nếu đã tới thời gian bảo trì tiếp theo
                            $record->status = 1;
                            $record->save();
                        }
                    }
                }
            }
        }

        return redirect()->route('imports.index')->with('msg', 'Tạo phiếu nhập hàng thành công!');
    }

    /**
     * Display the specified resource.
     */
    public function show(String $id)
    {
        $import = Imports::leftJoin("providers", "providers.id", "imports.provider_id")
            ->leftJoin("users", "users.id", "imports.user_id")
            ->select("providers.provider_name", "users.name", "imports.*")
            ->where("imports.id", $id)
            ->first();
        $productImports = ProductImport::where("import_id", $id)
            ->get()->groupBy('product_id');
        $title = "Xem chi tiết phiếu nhập hàng";
        $providers = Providers::all();

        return view('expertise.import.see', compact('title', 'import', 'productImports', 'providers'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(String $id)
    {
        $import = Imports::leftJoin("providers", "providers.id", "imports.provider_id")
            ->leftJoin("users", "users.id", "imports.user_id")
            ->select("providers.provider_name", "users.name", "imports.*")
            ->where("imports.id", $id)
            ->first();
        $title = "Sửa phiếu nhập hàng";
        $users = User::all();
        $providers = Providers::all();

        $productImports = ProductImport::where("import_id", $id)
            ->get()->groupBy('product_id');
        $productAll = Product::all();
        $data = $this->imports->getAllImports();
        return view('expertise.import.edit', compact('title', 'import', 'users', 'providers', 'productAll', 'productImports', 'data'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, String $id)
    {
        // dd($request->all());
        // Validate dữ liệu đầu vào
        $validatedData = $request->validate(
            [
                'import_code' => 'required|string|max:255|unique:imports,import_code,' . $id,
                'user_id'     => 'required|integer|exists:users,id',
                'phone'       => 'nullable|string|max:15',
                'date_create' => 'required|date',
                'provider_id' => 'required|integer|exists:providers,id',
                'address'     => 'nullable|string|max:255',
                'note'        => 'nullable|string|max:500',
            ],
            [
                'import_code.required' => 'Mã phiếu là bắt buộc.',
                'user_id.required'     => 'Người nhập là bắt buộc.',
                'date_create.required' => 'Ngày tạo là bắt buộc.',
            ]
        );

        // Tìm import theo ID
        $import = Imports::findOrFail($id);

        // Cập nhật dữ liệu
        $import->update($validatedData);

        // Cập nhật import_date của InventoryLookup
        InventoryLookup::whereIn('sn_id', ProductImport::where('import_id', $id)->pluck('sn_id'))
            ->update(['import_date' => $request->date_create, 'provider_id' => $request->provider_id]);

        // Lấy dữ liệu từ form gửi lên
        $dataTest = json_decode($request->input('data-test'), true);

        // Lấy danh sách serial từ data-test
        $formSerials = array_column($dataTest, 'serial');

        // Lấy serials đã có trong database
        $existingSerials = SerialNumber::whereIn('serial_code', $formSerials)->get();
        $existingSerialCodes = $existingSerials->pluck('serial_code')->toArray();
        $serialIds = $existingSerials->pluck('id', 'serial_code')->toArray(); // Map serial_code -> sn_id

        // Lấy danh sách các serial cần thêm mới
        $newSerials = array_filter($dataTest, function ($data) use ($existingSerialCodes) {
            return isset($data['serial']) && !empty($data['serial']) && !in_array($data['serial'], $existingSerialCodes);
        });

        // Thêm mới các serials
        foreach ($newSerials as $serialData) {
            if (isset($serialData['serial']) && !empty($serialData['serial'])) {
                $newSerial = SerialNumber::create([
                    'serial_code' => $serialData['serial'],
                    'product_id' => $serialData['product_id'], // Nếu cần lưu product_id
                ]);
                // Thêm sn_id vào danh sách serialIds để dùng sau
                $serialIds[$serialData['serial']] = $newSerial->id;

                // **Thêm mới vào InventoryLookup nếu chưa tồn tại**
                $existingInventory = InventoryLookup::where('sn_id', $newSerial->id)->first();
                if (!$existingInventory) {
                    InventoryLookup::create([
                        'product_id' => $serialData['product_id'],
                        'sn_id' => $newSerial->id,
                        'provider_id' => $request->provider_id,
                        'import_date' => $request->date_create,
                        'storage_duration' => 0,
                        'status' => 0,
                    ]);
                }
            }
        }

        // Thêm mới hoặc cập nhật ProductImport
        foreach ($dataTest as $data) {
            if (isset($data['serial']) && !empty($data['serial']) && isset($serialIds[$data['serial']])) {
                $snId = $serialIds[$data['serial']];

                ProductImport::updateOrCreate(
                    [
                        'import_id' => $id, // import_id từ form
                        'product_id' => $data['product_id'], // Đảm bảo product_id từ data-test
                        'sn_id' => $snId, // Liên kết với serial vừa tạo
                    ],
                    [
                        'note' => $data['note_seri'] ?? '', // Ghi chú nếu có
                    ]
                );
            }
        }

        // Lấy danh sách sn_id từ form (chỉ lấy các giá trị hợp lệ)
        $formSerialIds = array_filter(array_map(function ($data) use ($serialIds) {
            return isset($data['serial']) && !empty($data['serial']) ? $serialIds[$data['serial']] ?? null : null;
        }, $dataTest));

        // Tìm các serial bị xóa khỏi form
        $removedSnIds = ProductImport::where('import_id', $id)
            ->whereNotIn('sn_id', $formSerialIds)
            ->pluck('sn_id'); // Lấy danh sách sn_id trước khi xóa

        // Xóa các serial không còn trong form
        ProductImport::where('import_id', $id)
            ->whereNotIn('sn_id', $formSerialIds)
            ->delete();

        if ($removedSnIds->isNotEmpty()) {
            SerialNumber::whereIn('id', $removedSnIds)->delete();

            // **Xóa khỏi InventoryLookup nếu tồn tại**
            InventoryLookup::whereIn('sn_id', $removedSnIds)->delete();
        }

        $records = InventoryLookup::all();
        foreach ($records as $record) {
            // Tính thời gian tồn kho
            $receivedDate = Carbon::parse($record->import_date); // Ngày nhập kho
            $storageDuration = $receivedDate->diffInDays(Carbon::now()); // Tính số ngày tồn kho

            // Cập nhật thời gian tồn kho
            $record->update(['storage_duration' => $storageDuration]);

            // Kiểm tra lần bảo trì đầu tiên hoặc các lần sau
            if ($storageDuration >= 90 && !$record->warranty_date) {
                // Lần bảo trì đầu tiên
                $record->status = 1;
                $record->save();
            } else if ($record->warranty_date) {
                // Các lần bảo trì tiếp theo
                $nextMaintenanceDate = Carbon::parse($record->warranty_date)->addDays(90);

                if (Carbon::now()->greaterThanOrEqualTo($nextMaintenanceDate)) {
                    // Nếu đã tới thời gian bảo trì tiếp theo
                    $record->status = 1;
                    $record->save();
                }
            } else {
                $record->status = 0;
                $record->save();
            }
        }

        return redirect()->route('imports.index')->with('msg', 'Cập nhật thành công phiếu nhập hàng!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(String $id)
    {
        $import = Imports::findOrFail($id);
        $productImports = ProductImport::where('import_id', $id)->get();

        foreach ($productImports as $productImport) {
            $exists = SerialNumber::where('id', $productImport->sn_id)
                ->where('status', 1)
                ->exists();
            if (!$exists) {
                return redirect()->route('imports.index')->with('warning', 'Xóa thất bại: Có SerialNumber không hợp lệ.');
            }
        }

        // Nếu tất cả SerialNumber hợp lệ, thực hiện xóa
        foreach ($productImports as $productImport) {
            $inventoryLookups = InventoryLookup::where('sn_id', $productImport->sn_id)->get();
            foreach ($inventoryLookups as $inventoryLookup) {
                InventoryHistory::where('inventory_lookup_id', $inventoryLookup->id)->delete();
            }
            InventoryLookup::where('sn_id', $productImport->sn_id)->delete();
            SerialNumber::where('id', $productImport->sn_id)->delete();
            $productImport->delete();
        }

        if (!ProductImport::where('import_id', $id)->exists()) {
            $import->delete();
        }

        return redirect()->route('imports.index')->with('msg', 'Xóa thành công phiếu nhập hàng!');
    }
    public function searchMiniView(Request $request)
    {
        // Định dạng lại ngày truyền vào để chắc chắn không có phần giờ
        $fromDate = Carbon::parse($request->fromDate)->format('Y-m-d');
        $toDate = Carbon::parse($request->toDate)->format('Y-m-d');

        if ($request->page == "NH") {
            $imports = Imports::query()
                ->when($request->idGuest, function ($query, $idGuest) {
                    return $query->where('provider_id', $idGuest);
                })
                ->when($request->creator, function ($query, $creator) {
                    return $query->where('imports.user_id', $creator);
                })
                ->whereBetween(DB::raw("DATE(imports.date_create)"), [$fromDate, $toDate])
                ->with('user', 'provider')
                ->get();

            $data = $imports->map(function ($detail) {
                return [
                    'id' => $detail->id,
                    'import_code' => $detail->import_code,
                    'date_create' => Carbon::parse($detail->date_create)->setTimezone('Asia/Ho_Chi_Minh')->format('d/m/Y'),
                    'provider_name' => $detail->provider->provider_name,
                ];
            });

            return response()->json($data);
        }
        if ($request->page == "XH") {
            $exports = Exports::query()
                ->when($request->idGuest, function ($query, $idGuest) {
                    return $query->where('customer_id', $idGuest);
                })
                ->when($request->creator, function ($query, $creator) {
                    return $query->where('exports.user_id', $creator);
                })
                ->whereBetween(DB::raw("DATE(exports.date_create)"), [$fromDate, $toDate])
                ->with('user', 'customer')
                ->get();

            $data = $exports->map(function ($detail) {
                return [
                    'id' => $detail->id,
                    'export_code' => $detail->export_code,
                    'date_create' => Carbon::parse($detail->date_create)->setTimezone('Asia/Ho_Chi_Minh')->format('d/m/Y'),
                    'customer_name' => $detail->customer->customer_name,
                ];
            });

            return response()->json($data);
        }
        if ($request->page == "TN") {
            $receiving = Receiving::query()
                ->when($request->idGuest, function ($query, $idGuest) {
                    return $query->where('customer_id', $idGuest);
                })
                ->when($request->creator, function ($query, $creator) {
                    return $query->where('receiving.user_id', $creator);
                })
                ->whereBetween(DB::raw("DATE(receiving.date_created)"), [$fromDate, $toDate])
                ->with('user', 'customer')
                ->get();

            $data = $receiving->map(function ($detail) {
                return [
                    'id' => $detail->id,
                    'form_code_receiving' => $detail->form_code_receiving,
                    'status' => match ($detail->status) {
                        1 => 'Tiếp nhận',
                        2 => 'Xử lý',
                        3 => 'Hoàn thành',
                        4 => 'Khách không đồng ý',
                    },
                    'date_create' => Carbon::parse($detail->date_created)->setTimezone('Asia/Ho_Chi_Minh')->format('d/m/Y'),
                    'customer_name' => $detail->customer->customer_name,
                ];
            });

            return response()->json($data);
        }
        if ($request->page == "BG") {
            $quotation = Quotation::query()
                ->when($request->idGuest, function ($query, $idGuest) {
                    return $query->where('customer_id', $idGuest);
                })
                ->when($request->creator, function ($query, $creator) {
                    return $query->where('quotations.user_id', $creator);
                })
                ->whereBetween(DB::raw("DATE(quotations.quotation_date)"), [$fromDate, $toDate])
                ->with('customer')
                ->get();

            $data = $quotation->map(function ($detail) {
                return [
                    'id' => $detail->id,
                    'quotation_code' => $detail->quotation_code,
                    'form_type' => match ($detail->reception->form_type) {
                        1 => 'Bảo hành',
                        2 => 'Dịch vụ',
                        3 => 'Dịch vụ bảo hành',
                    },
                    'date_create' => Carbon::parse($detail->quotation_date)->setTimezone('Asia/Ho_Chi_Minh')->format('d/m/Y'),
                    'customer_name' => $detail->customer->customer_name,
                ];
            });

            return response()->json($data);
        }
        if ($request->page == "TH") {
            $returnForm = ReturnForm::query()
                ->when($request->idGuest, function ($query, $idGuest) {
                    return $query->where('customer_id', $idGuest);
                })
                ->when($request->creator, function ($query, $creator) {
                    return $query->where('return_form.user_id', $creator);
                })
                ->whereBetween(DB::raw("DATE(return_form.date_created)"), [$fromDate, $toDate])
                ->with('productReturns', 'reception', 'customer')
                ->get();

            $data = $returnForm->map(function ($detail) {
                return [
                    'id' => $detail->id,
                    'return_code' => $detail->return_code,
                    'status' => match ((int) $detail->status) {
                        1 => 'Hoàn thành',
                        2 => 'Khách không đồng ý',
                        default => 'Trạng thái không xác định',
                    },
                    'date_create' => Carbon::parse($detail->date_created)->setTimezone('Asia/Ho_Chi_Minh')->format('d/m/Y'),
                    'customer_name' => $detail->customer->customer_name,
                ];
            });

            return response()->json($data);
        }
    }
    public function filterData(Request $request)
    {
        $data = $request->all();
        $filters = [];
        if (isset($data['ma']) && $data['ma'] !== null) {
            $filters[] = ['value' => 'Mã: ' . $data['ma'], 'name' => 'ma-phieu', 'icon' => 'po'];
        }
        if (isset($data['note']) && $data['note'] !== null) {
            $filters[] = ['value' => 'Ghi chú: ' . $data['note'], 'name' => 'ghi-chu', 'icon' => 'po'];
        }
        if (isset($data['user']) && $data['user'] !== null) {
            $filters[] = ['value' => 'Người lập phiếu: ' . count($data['user']) . ' đã chọn', 'name' => 'nguoi-lap-phieu', 'icon' => 'user'];
        }
        if (isset($data['provider']) && $data['provider'] !== null) {
            $filters[] = ['value' => 'Nhà cung cấp: ' . count($data['provider']) . ' đã chọn', 'name' => 'nha-cung-cap', 'icon' => 'user'];
        }
        if (isset($data['date']) && $data['date'][1] !== null) {
            $date_start = date("d/m/Y", strtotime($data['date'][0]));
            $date_end = date("d/m/Y", strtotime($data['date'][1]));
            $filters[] = ['value' => 'Ngày lập phiếu: từ ' . $date_start . ' đến ' . $date_end, 'name' => 'ngay-lap-phieu', 'icon' => 'date'];
        }
        if ($request->ajax()) {
            $imports = $this->imports->getImportAjax($data);
            return response()->json([
                'data' => $imports,
                'filters' => $filters,
            ]);
        }
        return false;
    }
}
