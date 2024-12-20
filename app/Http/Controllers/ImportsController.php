<?php

namespace App\Http\Controllers;

use App\Models\Imports;
use App\Models\InventoryLookup;
use App\Models\Product;
use App\Models\ProductImport;
use App\Models\Providers;
use App\Models\SerialNumber;
use App\Models\User;
use Illuminate\Http\Request;

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
        return view('expertise.import.index', compact('title', 'imports'));
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
                    'storage_duration' => 1,
                    'status' => 0,
                ]);
            }
        }

        return redirect()->route('imports.index')->with('msg', 'Tạo phiếu nhập hàng thành công!');;
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
        $products = ProductImport::where('import_id', $id)
            ->leftJoin("imports", "product_import.product_id", "imports.id")
            ->leftJoin("serial_numbers", "product_import.sn_id", "serial_numbers.id")
            ->leftJoin("products", "products.id", "product_import.product_id")
            ->select("serial_numbers.serial_code", "product_import.note as ghichu", "imports.*", "products.*")
            ->get();
        $title = "Xem chi tiết phiếu nhập hàng";
        return view('expertise.import.see', compact('title', 'import', 'products'));
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
        return view('expertise.import.edit', compact('title', 'import', 'users', 'providers', 'productAll', 'productImports'));
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
                        'storage_duration' => 1,
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

        return redirect()->route('imports.index')->with('msg', 'Cập nhật thành công phiếu nhập hàng!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(String $id)
    {
        $import = Imports::findOrFail($id);
        ProductImport::where('import_id', $id)->delete();
        $import->delete();
        return redirect()->route('imports.index')->with('msg', 'Xóa thành công phiếu nhập hàng!');
    }
}
