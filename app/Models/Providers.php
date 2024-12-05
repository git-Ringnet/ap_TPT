<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Providers extends Model
{
    use HasFactory;
    protected $table = 'providers';
    protected $fillable = [
        'id',
        'group_id',
        'provider_code',
        'provider_name',
        'address',
        'phone',
        'email',
        'tax_code',
        'note',
    ];
    public function getGroup()
    {
        return $this->hasOne(Groups::class, 'id', 'group_id');
    }
    public function getAllProvide()
    {
        return Providers::get();
    }
    public function addProvide($data)
    {
        $result = [];
        $provides = DB::table($this->table)->where('provider_code', $data['provider_code'])
            ->orWhere('provider_name', $data['provider_name'])
            ->first();
        if ($provides) {
            $result = [
                'status' => true,
            ];
        } else {
            $dataProvide = [
                'group_id' => isset($data['category_id']) ? $data['category_id'] : 0,
                'provider_code' => $data['provider_code'],
                'provider_name' => $data['provider_name'],
                'address' => isset($data['address']) ? $data['address'] : null,
                'phone' => $data['phone'],
                'email' => $data['email'],
                'tax_code' => $data['tax_code'],
                'note' => $data['note'],
                'created_at' => Carbon::now()
            ];
            $provide_id =  DB::table($this->table)->insertGetId($dataProvide);
            if ($provide_id) {
                $result = [
                    'status' => false,
                    'id' => $provide_id
                ];
            }
        }
        return $result;
    }
    public function updateProvide($data, $id)
    {
        $exist = false;
        $check = DB::table($this->table)
            ->where('id', '!=', $id)
            ->where(function ($query) use ($data) {
                $query->where('provider_code', $data['provider_code'])
                    ->orWhere('provider_name', $data['provider_name']);
            })
            ->first();

        if ($check) {
            $exist = true;
        } else {
            $dataUpdate = [
                'provider_name' => $data['provider_name'],
                'provider_code' => $data['provider_code'],
                'address' => $data['address'],
                'phone' => $data['phone'],
                'email' => $data['email'],
                'tax_code' => $data['tax_code'],
                'note' => $data['note'],
                'group_id' => $data['category_id'],
                'updated_at' => Carbon::now(),
            ];
            Providers::where('id', $id)->update($dataUpdate);
            $exist = false;
        }
        return $exist;
    }
}
