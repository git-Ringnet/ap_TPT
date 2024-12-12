<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Receiving extends Model
{
    protected $table = 'receiving';

    protected $fillable = [
        'branch_id',
        'form_type',
        'form_code_receiving',
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
    public function getQuoteCount(string $prefix, $model, string $column)
    {
        // Lấy số thứ tự lớn nhất của mã phiếu hiện có
        $lastInvoiceNumber = $model::max($column);
        $lastNumber = 0;
        if ($lastInvoiceNumber) {
            preg_match("/{$prefix}(\d+)/", $lastInvoiceNumber, $matches);
            $lastNumber = isset($matches[1]) ? (int)$matches[1] : 0;
        }
        $newInvoiceNumber = $lastNumber + 1;
        $countFormattedInvoice = str_pad($newInvoiceNumber, 2, '0', STR_PAD_LEFT);
        $invoiceNumber = "{$prefix}{$countFormattedInvoice}";

        return $invoiceNumber;
    }
}
