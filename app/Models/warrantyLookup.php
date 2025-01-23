<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class warrantyLookup extends Model
{
    protected $table = 'warranty_lookup';

    protected $fillable = [
        'product_id',
        'sn_id',
        'customer_id',
        'export_return_date',
        'warranty',
        'name_warranty',
        'status',
        'name_status',
        'warranty_expire_date',
    ];
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }
    public function serialNumber()
    {
        return $this->belongsTo(SerialNumber::class, 'sn_id', 'id');
    }
    public function customer()
    {
        return $this->belongsTo(Customers::class, 'customer_id', 'id');
    }
    public function warrantyHistories()
    {
        return $this->hasMany(warrantyHistory::class, 'warranty_lookup_id');
    }
    public function  getWarranAjax($data = null)
    {
        $warrantyLookup = warrantyLookup::join('products', 'products.id', '=', 'warranty_lookup.product_id')
            ->join('serial_numbers', 'serial_numbers.id', '=', 'warranty_lookup.sn_id')
            ->join('customers', 'customers.id', '=', 'warranty_lookup.customer_id')
            ->with('product', 'serialNumber', 'customer')
            ->select(
                'warranty_lookup.*',
                'serial_numbers.serial_code as sericode',
                'products.*',
                'customers.customer_name as customername',
                'warranty_lookup.status as status',
                'warranty_lookup.id as id',
                'warranty_lookup.warranty as warrantyLookup',
            );
        if (!empty($data['search'])) {
            $warrantyLookup->where(function ($query) use ($data) {
                $query->where('products.product_code', 'like', '%' . $data['search'] . '%')
                    ->orWhere('products.product_name', 'like', '%' . $data['search'] . '%')
                    ->orWhere('products.brand', 'like', '%' . $data['search'] . '%')
                    ->orWhere('customers.customer_name', 'like', '%' . $data['search'] . '%')
                    ->orWhere('serial_numbers.serial_code', 'like', '%' . $data['search'] . '%');
            });
        }
        $filterableFields = [
            'ma' => 'products.product_code',
            'ten' => 'products.product_name',
            'brand' => 'products.brand',
            'sn' => 'serial_numbers.serial_code',

        ];
        foreach ($filterableFields as $key => $field) {
            if (!empty($data[$key])) {
                $warrantyLookup->where($field, 'like', '%' . $data[$key] . '%');
            }
        }
        if (!empty($data['customer'])) {
            $warrantyLookup->whereHas('customer', function ($query) use ($data) {
                $query->whereIn('id', $data['customer']);
            });
        }
        if (isset($data['status'])) {
            $warrantyLookup = $warrantyLookup->whereIn('warranty_lookup.status', $data['status']);
        }
        if (!empty($data['date'][0]) && !empty($data['date'][1])) {
            $dateStart = Carbon::parse($data['date'][0]);
            $dateEnd = Carbon::parse($data['date'][1])->endOfDay();
            $warrantyLookup->whereBetween('export_return_date', [$dateStart, $dateEnd]);
        }
        if (isset($data['bao_hanh'][0]) && isset($data['bao_hanh'][1])) {
            $warrantyLookup = $warrantyLookup->where('warranty_lookup.warranty', $data['bao_hanh'][0], $data['bao_hanh'][1]);
        }
        if (isset($data['sort']) && isset($data['sort'][0])) {
            $warrantyLookup = $warrantyLookup->orderBy($data['sort'][0], $data['sort'][1]);
        }
        return $warrantyLookup->get();
    }
}