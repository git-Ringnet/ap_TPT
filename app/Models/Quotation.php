<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Quotation extends Model
{
    use HasFactory;

    protected $table = 'quotations';

    // Các trường có thể gán giá trị hàng loạt
    protected $fillable = [
        'reception_id',
        'quotation_code',
        'customer_id',
        'address',
        'quotation_date',
        'contact_person',
        'notes',
        'user_id',
        'contact_phone',
        'total_amount',
    ];

    // Nếu cần định nghĩa quan hệ
    public function customer()
    {
        return $this->belongsTo(Customers::class, 'customer_id');
    }

    public function reception()
    {
        return $this->belongsTo(Receiving::class, 'reception_id');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
    public function services()
    {
        return $this->hasMany(QuotationService::class, 'quotation_id');
    }
}
