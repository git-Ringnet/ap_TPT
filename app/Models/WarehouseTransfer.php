<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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
    public function getAllWarehouseTransfer($data = null)
    {
        $warehouse = DB::table('warehouse_transfers')
            ->leftJoin('warehouses', 'warehouses.id', 'warehouse_transfers.from_warehouse_id')
            ->select('warehouse_transfers.*','warehouses.warehouse_name','warehouses.warehouse_code');
        // Tìm kiếm chung
        if (!empty($data['search'])) {
            $warehouse->where(function ($query) use ($data) {
                $query->where('warehouse_transfers.code', 'like', '%' . $data['search'] . '%')
                    ->orWhere('warehouse_name', 'like', '%' . $data['search'] . '%')
                    ->orWhere('warehouse_transfers.note', 'like', '%' . $data['search'] . '%')
                    ->orWhere('warehouse_code', 'like', '%' . $data['search'] . '%');
            });
        }
        // Lọc theo các trường cụ thể
        $filterableFields = [
            'ma' => 'warehouse_transfers.code',
            'note' => 'warehouse_transfers.note',
        ];
        foreach ($filterableFields as $key => $field) {
            if (!empty($data[$key])) {
                $warehouse->where($field, 'like', '%' . $data[$key] . '%');
            }
        }
        if (!empty($data['date'][0]) && !empty($data['date'][1])) {
            $dateStart = Carbon::parse($data['date'][0]);
            $dateEnd = Carbon::parse($data['date'][1])->endOfDay();
            $warehouse->whereBetween('transfer_date', [$dateStart, $dateEnd]);
        }
        if (isset($data['status'])) {
            $warehouse = $warehouse->whereIn('warehouse_transfers.status', $data['status']);
        }
        if (isset($data['kho_chuyen'])) {
            $warehouse = $warehouse->whereIn('warehouse_transfers.from_warehouse_id', $data['kho_chuyen']);
        }
        if (isset($data['kho_nhan'])) {
            $warehouse = $warehouse->whereIn('warehouse_transfers.to_warehouse_id', $data['kho_nhan']);
        }
        if (isset($data['sort']) && isset($data['sort'][0])) {
            $warehouse = $warehouse->orderBy($data['sort'][0], $data['sort'][1]);
        }
        return $warehouse->get();
    }
}
