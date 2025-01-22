<?php

namespace App\Models;

use App\Helpers\GlobalHelper;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class Exports extends Model
{

    use HasFactory;
    protected $table = 'exports';

    protected $fillable = [
        'export_code',
        'user_id',
        'phone',
        'date_create',
        'customer_id',
        'contact_person',
        'address',
        'note',
        'warehouse_id',
    ];
    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class, 'warehouse_id', 'id');
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function customer()
    {
        return $this->belongsTo(Customers::class, 'customer_id', 'id');
    }

    public static function generateExportCode()
    {
        $prefix = 'PXH';

        // Lấy mã lớn nhất hiện tại theo prefix
        $lastCode = DB::table('exports')
            ->where('export_code', 'like', "{$prefix}%")
            ->orderBy('export_code', 'desc')
            ->value('export_code');

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

    public function addExport($data)
    {
        $warehouse_id = GlobalHelper::getWarehouseId();
        $arrExport = [
            'export_code' => $data['export_code'],
            'user_id' => $data['user_id'],
            'phone' => $data['phone'],
            'date_create' => $data['date_create'],
            'customer_id' => $data['customer_id'],
            'address' => $data['address'],
            'contact_person' => $data['contact_person'],
            'note' => $data['note'],
            'created_at' => now(),
            'warehouse_id' => $warehouse_id ?? 1,
        ];
        $import = DB::table($this->table)->insertGetId($arrExport);
        return $import;
    }
    public function getExportAjax($data = null)
    {
        $exports = Exports::with(['user', 'customer'])
            ->select('exports.*', 'users.name as username', 'customers.customer_name as customername')
            ->join('users', 'exports.user_id', '=', 'users.id') // Join với bảng users
            ->join('customers', 'exports.customer_id', '=', 'customers.id');
        if (!empty($data)) {
            if (!empty($data['search'])) {
                $exports->where(function ($query) use ($data) {
                    $query->where('export_code', 'like', '%' . $data['search'] . '%')
                        ->orWhere('note', 'like', '%' . $data['search'] . '%');
                });
            }
            if (!empty($data['ma'])) {
                $exports->where('export_code', 'like', '%' . $data['ma'] . '%');
            }
            if (!empty($data['note'])) {
                $exports->where('note', 'like', '%' . $data['note'] . '%');
            }
            if (!empty($data['date'][0]) && !empty($data['date'][1])) {
                $dateStart = Carbon::parse($data['date'][0]);
                $dateEnd = Carbon::parse($data['date'][1])->endOfDay();
                $exports->whereBetween('date_create', [$dateStart, $dateEnd]);
            }
            if (!empty($data['customer'])) {
                $exports->whereHas('customer', function ($query) use ($data) {
                    $query->whereIn('id', $data['customer']);
                });
            }
            if (!empty($data['user'])) {
                $exports->whereHas('user', function ($query) use ($data) {
                    $query->whereIn('id', $data['user']);
                });
            }
        }
        if (isset($data['sort']) && isset($data['sort'][0])) {
            $exports = $exports->orderBy($data['sort'][0], $data['sort'][1]);
        }
        return $exports->get();
    }
}
