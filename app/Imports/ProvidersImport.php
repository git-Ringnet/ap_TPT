<?php

namespace App\Imports;

use App\Models\Providers;
use Maatwebsite\Excel\Concerns\ToModel;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

class ProvidersImport implements ToCollection
{
    public $duplicates = [];

    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            $provider_name = $row[1] ?? null;
            $tax_code = $row[6] ?? null;

            // Tạo truy vấn kiểm tra nhà cung cấp đã tồn tại hay chưa
            $query = Providers::where('provider_name', $provider_name);

            // Nếu tax_code không null, thêm điều kiện kiểm tra tax_code
            if (!empty($tax_code)) {
                $query->orWhere('tax_code', $tax_code);
            }

            // Lấy nhà cung cấp đầu tiên tìm thấy
            $existingProvider = $query->first();

            if ($existingProvider) {
                // Nếu có nhà cung cấp trùng, lưu id vào mảng duplicates
                $this->duplicates[] = [
                    'provider_id' => $existingProvider->id, // Lưu id của nhà cung cấp trùng
                    'row_data' => $row // Lưu dữ liệu hàng trùng lặp
                ];
            } else {
                // Nếu không có trùng lặp, thêm nhà cung cấp mới vào cơ sở dữ liệu
                Providers::create([
                    'provider_code' => $row[0] ?? null,
                    'provider_name' => $provider_name,
                    'address' => $row[2] ?? null,
                    'contact_person' => $row[3] ?? null,
                    'phone' => $row[4] ?? null,
                    'email' => $row[5] ?? null,
                    'tax_code' => $tax_code,
                    'note' => $row[7] ?? null,
                ]);
            }
        }
    }

    // Phương thức trả về các nhà cung cấp bị trùng lặp với id
    public function getDuplicates()
    {
        return $this->duplicates;
    }
}
