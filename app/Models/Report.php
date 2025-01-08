<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Report extends Model
{
    public function mergeProductData($filter, $data)
    {
        $mergedData = [];

        // Xử lý imports
        foreach ($data['imports'] as $import) {
            $productId = $import->id;
            $mergedData[$productId] = [
                'id' => $productId,
                'product_import' => $import->product_import,
                'product_export' => 0,
                'product_code' => $import->product_code,
                'product_name' => $import->product_name,
            ];
        }

        // Xử lý exports
        foreach ($data['exports'] as $export) {
            $productId = $export->id;
            if (isset($mergedData[$productId])) {
                $mergedData[$productId]['product_export'] = $export->product_export;
            } else {
                $mergedData[$productId] = [
                    'id' => $productId,
                    'product_import' => 0,
                    'product_export' => $export->product_export,
                    'product_code' => $export->product_code,
                    'product_name' => $export->product_name,
                ];
            }
        }

        // Áp dụng bộ lọc search cho product_code và product_name
        if (!empty($filter['search'])) {
            $mergedData = array_filter($mergedData, function ($item) use ($filter) {
                return strpos(strtolower($item['product_code']), strtolower($filter['search'])) !== false ||
                    strpos(strtolower($item['product_name']), strtolower($filter['search'])) !== false;
            });
        }

        // Áp dụng các bộ lọc cho product_code và product_name từ filterableFields
        $filterableFields = [
            'ma' => 'product_code',
            'ten' => 'product_name',
        ];

        foreach ($filterableFields as $key => $field) {
            if (!empty($filter[$key])) {
                $mergedData = array_filter($mergedData, function ($item) use ($filter, $field) {
                    return strpos(strtolower($item[$field]), strtolower($filter[$key])) !== false;
                });
            }
        }
        // Lọc theo số lượng nhập và xuất
        if (isset($filter['so_luong_nhap'][0]) && isset($filter['so_luong_nhap'][1])) {
            $operator = $filter['so_luong_nhap'][0];
            $value = $filter['so_luong_nhap'][1];

            $mergedData = array_filter($mergedData, function ($item) use ($operator, $value) {
                // So sánh với product_import
                if ($operator === '>=') {
                    return $item['product_import'] >= $value;
                } elseif ($operator === '<=') {
                    return $item['product_import'] <= $value;
                }
                return true; // Trả về true nếu không có toán tử phù hợp
            });
        }
        if (isset($filter['so_luong_xuat'][0]) && isset($filter['so_luong_xuat'][1])) {
            $operator = $filter['so_luong_xuat'][0];
            $value = $filter['so_luong_xuat'][1];
            $mergedData = array_filter($mergedData, function ($item) use ($operator, $value) {
                if ($operator === '>=') {
                    return $item['product_export'] >= $value;
                } elseif ($operator === '<=') {
                    return $item['product_export'] <= $value;
                }
            });
        }
        // Trả về mảng kết quả đã lọc
        return array_values($mergedData);
    }

    public function getProductsAjax($data = null)
    {
        // Hàm xử lý điều kiện lọc theo type (tháng, quý, năm)
        $applyFilters = function ($query, $data, $dateField) {
            if (isset($data['type']) && (isset($data['month']) || isset($data['quarter']) || isset($data['year']))) {
                if ($data['type'] === 'thang' && isset($data['month']) && isset($data['year'])) {
                    $query->whereYear($dateField, $data['year'])
                        ->whereMonth($dateField, $data['month']);
                } elseif ($data['type'] === 'quy' && isset($data['quarter']) && isset($data['year'])) {
                    $startMonth = (($data['quarter'] - 1) * 3) + 1;
                    $endMonth = $startMonth + 2;
                    $query->whereYear($dateField, $data['year'])
                        ->whereBetween(DB::raw("MONTH($dateField)"), [$startMonth, $endMonth]);
                } elseif ($data['type'] === 'nam' && isset($data['year'])) {
                    $query->whereYear($dateField, $data['year']);
                }
            }
        };

        // Truy vấn nhập
        $imports = DB::table('product_import')
            ->leftJoin('imports', 'product_import.import_id', '=', 'imports.id')
            ->leftJoin('products', 'products.id', '=', 'product_import.product_id')
            ->where(function ($query) use ($applyFilters, $data) {
                $applyFilters($query, $data, 'imports.date_create');
            })
            ->select(
                'product_import.product_id as id',
                'products.product_code',
                'products.product_name',
                DB::raw('COUNT(product_import.product_id) as product_import')
            )
            ->groupBy('product_import.product_id', 'products.product_code', 'products.product_name')
            ->get();

        // Truy vấn xuất
        $exports = DB::table('product_export')
            ->leftJoin('exports', 'product_export.export_id', '=', 'exports.id')
            ->leftJoin('products', 'products.id', '=', 'product_export.product_id')
            ->where(function ($query) use ($applyFilters, $data) {
                $applyFilters($query, $data, 'exports.date_create');
            })
            ->select(
                'product_export.product_id as id',
                'products.product_code',
                'products.product_name',
                DB::raw('COUNT(product_export.product_id) as product_export')
            )
            ->groupBy('product_export.product_id', 'products.product_code', 'products.product_name')
            ->get();

        // Trả về kết quả
        return compact('imports', 'exports');
    }

    // public function getProductsAjax($data = null)
    // {
    //     $products = DB::table('products')
    //         ->leftJoin(
    //             DB::raw('(SELECT product_id, SUM(quantity) as total_import FROM product_import GROUP BY product_id) as imports'),
    //             'products.id',
    //             '=',
    //             'imports.product_id'
    //         )
    //         ->leftJoin(
    //             DB::raw('(SELECT product_id, SUM(quantity) as total_export FROM product_export GROUP BY product_id) as exports'),
    //             'products.id',
    //             '=',
    //             'exports.product_id'
    //         )
    //         ->select(
    //             'products.id',
    //             'products.product_code',
    //             'products.product_name',
    //             DB::raw('COALESCE(imports.total_import, 0) as total_import'),
    //             DB::raw('COALESCE(exports.total_export, 0) as total_export')
    //         );
    // if (!empty($data['search'])) {
    //     $products->where(function ($query) use ($data) {
    //         $query->where('products.product_code', 'like', '%' . $data['search'] . '%')
    //             ->orWhere('products.product_name', 'like', '%' . $data['search'] . '%');
    //     });
    // }
    // $filterableFields = [
    //     'ma' => 'products.product_code',
    //     'ten' => 'products.product_name',

    // ];
    // foreach ($filterableFields as $key => $field) {
    //     if (!empty($data[$key])) {
    //         $products->where($field, 'like', '%' . $data[$key] . '%');
    //     }
    // }
    // if (isset($data['so_luong_nhap'][0]) && isset($data['so_luong_nhap'][1])) {
    //     $products = $products->having('total_import', $data['so_luong_nhap'][0], $data['so_luong_nhap'][1]);
    // }
    // if (
    //     isset($data['so_luong_xuat'][0]) && isset($data['so_luong_xuat'][1])
    // ) {
    //     $products = $products->having('total_export', $data['so_luong_xuat'][0], $data['so_luong_xuat'][1]);
    // }
    //     return $products->get();
    // }
}
