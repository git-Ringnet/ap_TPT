<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class WarehouseTransfer extends Model
{
    public static function generateExportCode()
    {
        $prefix = 'PCK';

        // Lấy mã lớn nhất hiện tại theo prefix
        $lastCode = WarehouseTransfer::where('code', 'like', "{$prefix}%")
            ->orderBy('code', 'desc')
            ->value('code');

        // Tách số thứ tự nếu mã cuối cùng tồn tại
        $newNumber = 1; // Mặc định số thứ tự là 1
        if ($lastCode) {
            $lastNumber = (int) substr($lastCode, strlen($prefix)); // Lấy phần số sau prefix
            $newNumber  = $lastNumber + 1;
        }

        // Định dạng số thứ tự thành chuỗi 5 chữ số (001, 002, ...)
        $formattedNumber = str_pad($newNumber, 5, '0', STR_PAD_LEFT);

        // Kết hợp thành mã mới
        return "{$prefix}{$formattedNumber}";
    }
    //funtion add
    public static function add($data)
    {
        // Tạo phiếu chuyển kho
        $warehouseTransfer = new WarehouseTransfer();
        $warehouseTransfer->code = $data['code'];
        $warehouseTransfer->from_warehouse_id = $data['from_warehouse_id'];
        $warehouseTransfer->to_warehouse_id = $data['to_warehouse_id'];
        $warehouseTransfer->status = 1;
        $warehouseTransfer->note = $data['note'];
        $warehouseTransfer->user_id = Auth::user()->id;
        $warehouseTransfer->save();
        
        return $warehouseTransfer->id;
    }

    //relationship warehouse
    public function fromWarehouse()
    {
        return $this->belongsTo(Warehouse::class, 'from_warehouse_id');
    }
    public function toWarehouse()
    {
        return $this->belongsTo(Warehouse::class, 'to_warehouse_id');
    }
    //relationship user
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    //function update
    public static function updateWarehouseTransfer($data, $id)
    {
        $exist = WarehouseTransfer::where('id', '!=', $id)
            ->where('code', $data['code'])
            ->first();
        if (!$exist) {
            $warehouseTransfer = WarehouseTransfer::find($id);
            $warehouseTransfer->code = $data['code'];
            $warehouseTransfer->from_warehouse_id = $data['from_warehouse_id'];
            $warehouseTransfer->to_warehouse_id = $data['to_warehouse_id'];
            $warehouseTransfer->transfer_date = $data['transfer_date'];
            $warehouseTransfer->status = 1;
            $warehouseTransfer->note = $data['note'];
            $warehouseTransfer->user_id = Auth::user()->id;
            $warehouseTransfer->save();
            return $warehouseTransfer;
        } else {
            return false;
        }
    }
}
