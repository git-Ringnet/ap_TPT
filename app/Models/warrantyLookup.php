<?php

namespace App\Models;

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
        'status',
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
}
