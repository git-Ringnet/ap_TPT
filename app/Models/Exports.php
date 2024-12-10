<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
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
        'address',
        'note',
    ];

    public static function generateExportCode()
    {
        $prefix = 'PXH';
        $date = now()->format('dmy'); // Ngày tháng năm hiện tại (ddmmyy)

        // Lấy mã lớn nhất hiện tại theo prefix và ngày
        $lastCode = DB::table('exports')
            ->where('export_code', 'like', "{$prefix}%" . "-{$date}")
            ->orderBy('export_code', 'desc')
            ->value('export_code');

        // Tách số thứ tự nếu mã cuối cùng tồn tại
        $newNumber = 1; // Mặc định số thứ tự là 1
        if ($lastCode) {
            $lastNumber = (int) substr($lastCode, strlen($prefix), 3); // Lấy 3 ký tự sau prefix
            $newNumber  = $lastNumber + 1;
        }

        // Định dạng số thứ tự thành chuỗi 3 chữ số (001, 002, ...)
        $formattedNumber = str_pad($newNumber, 3, '0', STR_PAD_LEFT);

        // Kết hợp thành mã mới
        return "{$prefix}{$formattedNumber}-{$date}";
    }

    public function addExport($data)
    {
        $arrExport = [
            'export_code' => $data['export_code'],
            'user_id' => $data['user_id'],
            'phone' => $data['phone'],
            'date_create' => $data['date_create'],
            'customer_id' => $data['customer_id'],
            'address' => $data['address'],
            'note' => $data['note'],
            'created_at' => now()
        ];
        $import = DB::table($this->table)->insertGetId($arrExport);
        return $import;
    }
    function getAllExports()
    {
        return Exports::leftJoin("customers", "customers.id", "exports.customer_id")
            ->leftJoin("users", "users.id", "exports.user_id")
            ->select("customers.customer_name", "users.name", "exports.*")
            ->get();
    }
}
