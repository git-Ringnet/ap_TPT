<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WarehouseTransferItem extends Model
{
    protected $fillable = [
        'transfer_id',
        'product_id',
        'serial_number_id',
        'sn_id_borrow',
        'note',
    ];
    function addItemWarehouseTransfer($data, $id)
    {
        // Kiểm tra dữ liệu đầu vào
        $dataTest = $data['data-test'] ?? null;
        if (!$dataTest) {
            return response()->json(['error' => 'Dữ liệu chuyển kho không hợp lệ'], 400);
        }

        // Giải mã JSON
        $uniqueProductsArray = json_decode($dataTest, true);
        if (!is_array($uniqueProductsArray)) {
            return response()->json(['error' => 'Dữ liệu sản phẩm không hợp lệ'], 400);
        }

        // Duyệt danh sách serials
        foreach ($uniqueProductsArray as $serial) {
            if (!isset($serial['serial']) || empty($serial['serial'])) {
                continue;
            }

            $trimmedSerial = trim($serial['serial']);
            $sn = null;

            if ($data['from_warehouse_id'] == 2) {
                // Nếu chuyển từ kho bảo hành về kho mới => Tạo Serial mới
                $sn = SerialNumber::create([
                    'serial_code' => $trimmedSerial,
                    'product_id' => $serial['product_id'],
                    'note' => $serial['note_seri'],
                    'warehouse_id' => $data['to_warehouse_id'],
                ]);
                //Tra cứu tồn kho
                InventoryLookup::create([
                    'product_id' => $serial['product_id'],
                    'sn_id' => $sn->id,
                    'provider_id' => 0,
                    'import_date' => $data['transfer_date'],
                    'storage_duration' => 0,
                    'status' => 0,
                ]);
                $snBr = SerialNumber::where("serial_code", $serial['serialBorrow'])->first();
                if ($snBr) {
                    $snBr->update([
                        'status' => 6, // Đánh dấu "Đã đổi cho khách hàng"
                    ]);
                }
            } else {
                // Nếu chuyển từ kho mới sang kho bảo hành => Cập nhật Serial cũ
                $sn = SerialNumber::where("serial_code", $trimmedSerial)->first();
                if ($sn) {
                    $sn->update([
                        'status' => 5, // Đánh dấu "Đang mượn"
                        'warehouse_id' => $data['to_warehouse_id'],
                    ]);
                }
            }

            // Nếu serial không tồn tại hoặc tạo thất bại, báo lỗi
            if (!$sn) {
                return response()->json([
                    'error' => "Không tìm thấy hoặc tạo Serial {$trimmedSerial} thất bại"
                ], 400);
            }

            // Xử lý serial mượn
            $trimmedSerialBorrow = isset($serial['serialBorrow']) ? trim($serial['serialBorrow']) : null;
            $snBr = $trimmedSerialBorrow ? SerialNumber::where("serial_code", $trimmedSerialBorrow)->first() : null;

            // Tạo WarehouseTransferItem
            WarehouseTransferItem::create([
                'transfer_id' => $id,
                'product_id' => $serial['product_id'],
                'serial_number_id' => $sn->id,
                'sn_id_borrow' => $snBr ? $snBr->id : null,
                'note' => $serial['note_seri'],
            ]);
        }
    }

    //update item warehouse transfer
    function updateItemWarehouseTransfer($data, $id)
    {
        $warehouseTransfer = WarehouseTransfer::find($id);
        // Lấy danh sách SerialNumber cũ trong phiếu chuyển kho
        $oldSerialNumbers = WarehouseTransferItem::where('transfer_id', $id)->pluck('serial_number_id')->toArray();

        // Lấy danh sách serial mới từ request
        $dataTest = $data['data-test'];
        $uniqueProductsArray = json_decode($dataTest, true);

        // Danh sách serial mới (lấy id nếu có)
        $newSerialNumbers = [];
        foreach ($uniqueProductsArray as $serial) {
            if (isset($serial['serial']) && !empty($serial['serial'])) {
                $trimmedSerial = str_replace(' ', '', $serial['serial']);
                $sn = SerialNumber::where("serial_code", $trimmedSerial)->first();
                if ($sn) {
                    $newSerialNumbers[] = $sn->id;
                }
            }
        }

        // Xử lý các serial bị xóa (không có trong danh sách mới)
        $serialsToReset = array_diff($oldSerialNumbers, $newSerialNumbers);
        SerialNumber::whereIn('id', $serialsToReset)->update([
            'status' => 1, // Cập nhật lại trạng thái
            'warehouse_id' => $warehouseTransfer->from_warehouse_id, // Trả về kho cũ
        ]);

        // Xóa các bản ghi cũ trong `warehouse_transfer_items`
        WarehouseTransferItem::where('transfer_id', $id)->delete();

        // Cập nhật lại danh sách serial
        foreach ($uniqueProductsArray as $serial) {
            if (isset($serial['serial']) && !empty($serial['serial'])) {
                if ($data['from_warehouse_id'] == 2) {
                    $exist = SerialNumber::where('serial_code', $serial['serial'])->first();
                    if (!$exist) {
                        // Nếu xuất từ kho bảo hành -> Tạo SN mới
                        $sn = SerialNumber::create([
                            'serial_code' => str_replace(' ', '', $serial['serial']),
                            'product_id' => $serial['product_id'],
                            'note' => $serial['note_seri'],
                            'warehouse_id' => $data['to_warehouse_id'],
                        ]);
                        //Tra cứu tồn kho
                        InventoryLookup::create([
                            'product_id' => $serial['product_id'],
                            'sn_id' => $sn->id,
                            'provider_id' => 0,
                            'import_date' => $data['transfer_date'],
                            'storage_duration' => 0,
                            'status' => 0,
                        ]);
                    }
                    $snBr = SerialNumber::where("serial_code", $serial['serialBorrow'])->first();
                    if ($snBr) {
                        $snBr->update([
                            'status' => 6, // Đánh dấu "Đã đổi cho khách hàng"
                        ]);
                    }
                } else {
                    // Nếu xuất từ kho mới -> Cập nhật SN có sẵn
                    $trimmedSerial = str_replace(' ', '', $serial['serial']);
                    $sn = SerialNumber::where("serial_code", $trimmedSerial)->first();
                    if ($sn) {
                        $sn->update([
                            'status' => 5, // Đang mượn
                            'warehouse_id' => $data['to_warehouse_id'],
                        ]);
                    }
                }

                if ($sn) {
                    // Kiểm tra Serial mượn
                    $trimmedSerialBorrow = isset($serial['serialBorrow']) ? str_replace(' ', '', $serial['serialBorrow']) : null;
                    $snBr = $trimmedSerialBorrow ? SerialNumber::where("serial_code", $trimmedSerialBorrow)->first() : null;

                    // Thêm mới vào bảng `warehouse_transfer_items`
                    WarehouseTransferItem::create([
                        'transfer_id' => $id,
                        'product_id' => $serial['product_id'],
                        'serial_number_id' => $sn->id,
                        'sn_id_borrow' => $snBr ? $snBr->id : null,
                        'note' => $serial['note_seri'],
                    ]);
                }
            }
        }
    }
    //relation product
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
    //relation serial number
    public function serialNumber()
    {
        return $this->belongsTo(SerialNumber::class);
    }
    //relation serial number borrow
    public function serialNumberBorrow()
    {
        return $this->belongsTo(SerialNumber::class, 'sn_id_borrow');
    }
}
