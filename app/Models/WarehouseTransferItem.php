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
        // Lấy danh sách serial mới từ request
        $dataTest = $data['data-test'] ?? null;
        if (!$dataTest) {
            return response()->json(['error' => 'Dữ liệu chuyển kho không hợp lệ'], 400);
        }

        $uniqueProductsArray = json_decode($dataTest, true);
        if (!is_array($uniqueProductsArray)) {
            return response()->json(['error' => 'Dữ liệu sản phẩm không hợp lệ'], 400);
        }

        // Duyệt danh sách serials để cập nhật `note_seri`
        foreach ($uniqueProductsArray as $serial) {
            if (!empty($serial['serial'])) {
                $trimmedSerial = str_replace(' ', '', $serial['serial']);

                WarehouseTransferItem::whereHas('serialNumber', function ($query) use ($trimmedSerial) {
                    $query->where('serial_code', $trimmedSerial);
                })->update([
                    'note' => $serial['note_seri'],
                ]);
            }
        }

        return response()->json(['success' => 'Cập nhật ghi chú thành công']);
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
