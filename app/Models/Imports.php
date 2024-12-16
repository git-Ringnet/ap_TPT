<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Imports extends Model
{
    use HasFactory;
    protected $table = 'imports';

    protected $fillable = [
        'import_code',
        'user_id',
        'phone',
        'date_create',
        'provider_id',
        'address',
        'note',
    ];

    public function productImports()
    {
        return $this->hasMany(ProductImport::class, 'import_id', 'id');
    }

    public function getAllImports()
    {
        return Imports::leftJoin("providers", "providers.id", "imports.provider_id")
            ->leftJoin("users", "users.id", "imports.user_id")
            ->select("providers.provider_name", "users.name", "imports.*")
            ->get();
    }

    public function addImport($data)
    {
        $arrImport = [
            'import_code' => $data['import_code'],
            'user_id' => $data['user_id'],
            'phone' => $data['phone'],
            'date_create' => $data['date_create'],
            'provider_id' => $data['provider_id'],
            'address' => $data['address'],
            'note' => $data['note'],
            'created_at' => now()
        ];
        $import = DB::table($this->table)->insertGetId($arrImport);
        return $import;
    }
    public static function generateImportCode()
    {
        $prefix = 'PNH';

        // Lấy mã lớn nhất hiện tại theo prefix
        $lastCode = DB::table('imports')
            ->where('import_code', 'like', "{$prefix}%")
            ->orderBy('import_code', 'desc')
            ->value('import_code');

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
}
