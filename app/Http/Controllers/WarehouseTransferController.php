<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\SerialNumber;
use App\Models\Warehouse;
use App\Models\WarehouseTransfer;
use App\Models\WarehouseTransferItem;
use Illuminate\Http\Request;

class WarehouseTransferController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    private $warehouseTransfer;
    private $warehouseTransferItem;
    public function __construct()
    {
        $this->warehouseTransfer = new WarehouseTransfer();
        $this->warehouseTransferItem = new WarehouseTransferItem();
    }
    public function index()
    {
        $title = 'Phiếu chuyển kho';
        $warehouseTransfer = $this->warehouseTransfer->orderBy('id', 'desc')->get();
        return view('expertise.warehouseTransfer.index', compact('title', 'warehouseTransfer'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $title = 'Phiếu chuyển kho';
        $export_code = $this->warehouseTransfer->generateExportCode();
        $warehouse = Warehouse::all();
        $products = Product::all();
        return view('expertise.warehouseTransfer.create', compact('title', 'export_code', 'warehouse', 'products'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->all();
        $id = $this->warehouseTransfer->add($data);
        $this->warehouseTransferItem->addItemWarehouseTransfer($data, $id);
        return redirect()->route('warehouseTransfer.index')->with('msg', 'Tạo mới phiếu chuyển kho thành công!');
    }

    /**
     * Display the specified resource.
     */
    public function show(WarehouseTransfer $warehouseTransfer)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(WarehouseTransfer $warehouseTransfer)
    {
        $title = 'Phiếu chuyển kho';
        $warehouseTransfer = WarehouseTransfer::findorFail($warehouseTransfer->id);
        $warehouse = Warehouse::all();
        $productAll = Product::all();
        $productWarehouse = WarehouseTransferItem::where('transfer_id', $warehouseTransfer->id)
            ->get()->groupBy('product_id');
        return view('expertise.warehouseTransfer.edit', compact('title', 'warehouseTransfer', 'warehouse', 'productWarehouse', 'productAll'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, WarehouseTransfer $warehouseTransfer)
    {
        $warehouseTransfer = WarehouseTransfer::findorFail($warehouseTransfer->id);
        $data = $request->all();
        $result = $this->warehouseTransfer->updateWarehouseTransfer($data, $warehouseTransfer->id);
        $this->warehouseTransferItem->updateItemWarehouseTransfer($data, $warehouseTransfer->id);
        if (!$result) {
            return redirect()->back()->with('warning', 'Mã phiếu đã tồn tại, vui lòng nhập mã khác!');
        }
        return redirect()->route('warehouseTransfer.index')->with('msg', 'Cập nhật phiếu chuyển kho thành công!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(WarehouseTransfer $warehouseTransfer)
    {
        //Xóa phiếu chuyển kho
        $warehouseTransfer = WarehouseTransfer::findorFail($warehouseTransfer->id);
        if ($warehouseTransfer) {
            $warehouseTransferItem = WarehouseTransferItem::where('transfer_id', $warehouseTransfer->id)->get();
            foreach ($warehouseTransferItem as $item) {
                if ($warehouseTransfer->from_warehouse_id == 1) {
                    $serial = SerialNumber::where('id', $item->serial_number_id)->first();
                    $serial->status = 1;
                    $serial->warehouse_id = $warehouseTransfer->from_warehouse_id;
                    $serial->save();
                    $item->delete();
                } else {
                    $serial = SerialNumber::where('id', $item->serial_number_id)->first();
                    $serial->delete();
                    $borrow = SerialNumber::where('id', $item->sn_id_borrow)->first();
                    $borrow->status = 5;
                    $borrow->warehouse_id = $warehouseTransfer->from_warehouse_id;
                    $borrow->save();
                    $item->delete();
                }
            }
            $warehouseTransfer->delete();
            return redirect()->route('warehouseTransfer.index')->with('msg', 'Xóa phiếu chuyển kho thành công!');
        } else {
            return redirect()->route('warehouseTransfer.index')->with('warning', 'Xóa phiếu chuyển kho thất bại!');
        }
    }
}
