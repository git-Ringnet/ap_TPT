<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Receiving extends Model
{
    protected $table = 'receiving';

    protected $fillable = [
        'branch_id',
        'form_type',
        'form_code',
        'customer_id',
        'address',
        'date_created',
        'contact_person',
        'notes',
        'user_id',
        'phone',
        'closed_at',
        'status',
        'state',
    ];

    protected $casts = [
        'date_created' => 'datetime',
        'closed_at' => 'datetime',
    ];
}
