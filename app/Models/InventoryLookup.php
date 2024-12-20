<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
}
