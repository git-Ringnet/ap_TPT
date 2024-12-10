<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Exports extends Model
{
    //
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
}
