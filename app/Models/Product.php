<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    // Đặt các cột có thể điền giá trị bằng phương thức create hoặc update
    protected $fillable = [
        'group_id',
        'product_code',
        'product_name',
        'brand',
        'warranty',
    ];

    /**
     * Thiết lập quan hệ nếu cần
     */

    public function serialNumbers()
    {
        return $this->hasMany(SerialNumber::class, 'product_id');
    }

    public function inventoryLookups()
    {
        return $this->hasMany(InventoryLookup::class, 'product_id', 'id');
    }

    // public function group()
    // {
    //     return $this->belongsTo(Group::class, 'group_id');
    // }

}
