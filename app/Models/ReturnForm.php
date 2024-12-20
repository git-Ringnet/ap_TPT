<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReturnForm extends Model
{
    use HasFactory;
    protected $table = 'return_form';
    protected $fillable = [
        'reception_id',
        'return_code',
        'customer_id',
        'address',
        'date_created',
        'contact_person',
        'return_method',
        'user_id',
        'phone_number',
        'notes',
        'status',
    ];

    /**
     * Relationship with Reception.
     */
    public function reception()
    {
        return $this->belongsTo(Receiving::class, 'reception_id');
    }

    /**
     * Relationship with Customer.
     */
    public function customer()
    {
        return $this->belongsTo(Customers::class, 'customer_id');
    }

    /**
     * Relationship with ProductReturn.
     */
    public function productReturns()
    {
        return $this->hasMany(ProductReturn::class, 'return_form_id');
    }
}
