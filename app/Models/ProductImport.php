<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductImport extends Model
{
    use HasFactory;
    protected $table = 'product_import';
    protected $fillable = [
        'import_id',
        'product_id',
        'quantity',
        'sn_id',
        'note'
    ];
}
