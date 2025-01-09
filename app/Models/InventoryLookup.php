<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class InventoryLookup extends Model
{
    use HasFactory;
    protected $table = 'inventory_lookup';

    protected $fillable = [
        'product_id',
        'sn_id',
        'provider_id',
        'import_date',
        'storage_duration',
        'status',
        'warranty_date',
        'note',
    ];
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }
    public function serialNumber()
    {
        return $this->belongsTo(SerialNumber::class, 'sn_id', 'id');
    }
    public function provider()
    {
        return $this->belongsTo(Providers::class, 'provider_id', 'id');
    }
    public function inventoryHistories()
    {
        return $this->hasMany(InventoryHistory::class, 'inventory_lookup_id');
    }

    public function  getInvenAjax($data = null)
    {
        $invenLookup = InventoryLookup::select('inventory_lookup.*')
            ->join('products', 'products.id', '=', 'inventory_lookup.product_id')
            ->join('serial_numbers', 'serial_numbers.id', '=', 'inventory_lookup.sn_id')
            ->join('providers', 'providers.id', '=', 'inventory_lookup.provider_id')
            ->with('product', 'serialNumber', 'provider');
        if (!empty($data['search'])) {
            $invenLookup->where(function ($query) use ($data) {
                $query->where('products.product_code', 'like', '%' . $data['search'] . '%')
                    ->orWhere('products.product_name', 'like', '%' . $data['search'] . '%')
                    ->orWhere('products.brand', 'like', '%' . $data['search'] . '%')
                    ->orWhere('providers.provider_name', 'like', '%' . $data['search'] . '%')
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
                $invenLookup->where($field, 'like', '%' . $data[$key] . '%');
            }
        }
        if (!empty($data['provider'])) {
            $invenLookup->whereHas('provider', function ($query) use ($data) {
                $query->whereIn('id', $data['provider']);
            });
        }
        if (isset($data['status'])) {
            $invenLookup = $invenLookup->whereIn('inventory_lookup.status', $data['status']);
        }
        if (!empty($data['date'][0]) && !empty($data['date'][1])) {
            $dateStart = Carbon::parse($data['date'][0]);
            $dateEnd = Carbon::parse($data['date'][1])->endOfDay();
            $invenLookup->whereBetween('import_date', [$dateStart, $dateEnd]);
        }
        if (isset($data['time_inven'][0]) && isset($data['time_inven'][1])) {
            $invenLookup = $invenLookup->where('storage_duration', $data['time_inven'][0], $data['time_inven'][1]);
        }
        return $invenLookup->get();
    }
}
