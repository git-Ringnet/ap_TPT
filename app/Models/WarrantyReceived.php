<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WarrantyReceived extends Model
{
    use HasFactory;

    protected $table = 'warranty_received';

    protected $fillable = [
        'product_received_id',
        'name_warranty',
        'state_recei',
        'note',
    ];

    // Relationship với bảng ProductReceived
    public function productReceived()
    {
        return $this->belongsTo(ReceivedProduct::class, 'product_received_id');
    }
}
