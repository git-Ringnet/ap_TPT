<?php

namespace App\Imports;

use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Collection;
use App\Models\Customers;

class CustomersImport implements ToCollection
{
    public $duplicates = [];

    public function collection(Collection $rows)
    {
        $rows = $rows->skip(1);
        foreach ($rows as $row) {
            $customer_name = $row[1] ?? null;
            $tax_code = $row[6] ?? null;
        
            // Tạo truy vấn cơ sở dữ liệu
            $query = Customers::where('customer_name', $customer_name);
        
            // Nếu tax_code không null, thêm điều kiện kiểm tra tax_code
            if (!empty($tax_code)) {
                $query->orWhere('tax_code', $tax_code);
            }
        
            // Lấy khách hàng đầu tiên tìm thấy
            $existingCustomer = $query->first();
        
            if ($existingCustomer) {
                // Nếu có khách hàng trùng, lưu id của khách hàng vào mảng duplicates
                $this->duplicates[] = [
                    'customer_id' => $existingCustomer->id, // Lưu id của khách hàng trùng
                    'row_data' => $row // Lưu dữ liệu hàng trùng lặp
                ];
            } else {
                // Nếu không có trùng lặp, thêm khách hàng mới vào cơ sở dữ liệu
                Customers::create([
                    'customer_code'  => $row[0],
                    'customer_name'  => $customer_name,
                    'address'        => $row[2] ?? null,
                    'contact_person' => $row[3] ?? null,
                    'phone'          => $row[4] ?? null,
                    'email'          => $row[5] ?? null,
                    'tax_code'       => $tax_code,
                    'note'           => $row[7] ?? null,
                ]);
            }
        }
        
    }
    
    // Phương thức trả về các khách hàng bị trùng lặp với id
    public function getDuplicates()
    {
        return $this->duplicates;
    }
    
}

