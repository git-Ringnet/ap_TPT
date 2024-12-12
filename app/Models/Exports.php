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
}
