<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Customers extends Model
{
    use HasFactory;
    protected $fillable = [
        'group_id',
        'customer_code',
        'customer_name',
        'address',
        'phone',
        'email',
        'tax_code',
    ];
    protected $table = 'customers';
    public function getAllGuest()
    {
        $guests = DB::table($this->table)->get();
        return $guests;
    }
    public function addGuest($data)
    {
        $exist = false;
        $guests = DB::table($this->table)
            ->where(function ($query) use ($data) {
                $query->where('customer_code', $data['customer_code'])
                    ->orWhere('customer_name', $data['customer_name']);
            })
            ->first();
        if ($guests) {
            $exist = true;
        } else {
            $dataguest = [
                'customer_code' => $data['customer_code'],
                'customer_name' => $data['customer_name'],
                'address' => $data['address'],
                'phone' => $data['phone'],
                'email' => $data['email'],
                'tax_code' => $data['tax_code'],
                'note' => $data['note'],
                'group_id' => isset($data['grouptype_id']) ? $data['grouptype_id'] : 0,
                'created_at' => now()
            ];
            DB::table($this->table)->insertGetId($dataguest);
        }
        return $exist;
    }
    public function updateCustomer($data, $id)
    {
        return DB::table($this->table)->where('id', $id)->update($data);
    }
}
