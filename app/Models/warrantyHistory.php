<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class warrantyHistory extends Model
{
    //
    use HasFactory;
    protected $table = 'warranty_history';

    protected $fillable = [
        'warranty_lookup_id',
        'receiving_id',
        'return_id',
        'note',
    ];
}
