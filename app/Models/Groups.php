<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use NunoMaduro\Collision\Provider;

class Groups extends Model
{
    use HasFactory;
    protected $table = 'groups';

    protected $fillable = [
        'group_type_id',
        'group_code',
        'group_name',
        'description',
    ];
    public function grouptype()
    {
        return $this->belongsTo(GrouptypeMain::class, 'group_type_id', 'id');
    }
    public function getAll()
    {
        return Groups::with('grouptype')->get();
    }

    public function getAllGroupedByType()
    {
        $groups = Groups::with('grouptype')->get();

        $groupedGroups = $groups->groupBy(function ($item) {
            return $item->groupType->group_name;
        });

        return $groupedGroups;
    }
    public function addGroup($data)
    {
        $existingGroup = Groups::where('group_name', $data['group_name'])
            ->where('group_type_id', $data['group_type_id'])
            ->first();

        if ($existingGroup) {
            return true;
        }
        $datagroup = [
            'group_code' => $data['group_code'],
            'group_name' => $data['group_name'],
            'group_type_id' => $data['group_type_id'],
            'description' => $data['description'],
            'created_at' => now(),
        ];

        DB::table($this->table)->insertGetId($datagroup);

        return false;
    }
    public function getDataGroup($id)
    {
        $grouptype = Groups::find($id);
        $grouptype = $grouptype->group_type_id;
        $data = [];
        if ($grouptype == 1) {
            $data = [
                'obj' => 'users',
                'results' => User::where('group_id', $id)
                    ->select('users.id as id', 'users.name as name')->get()
            ];
        } elseif ($grouptype == 2) {
            $data = [
                'obj' => 'guest',
                'results' => Customers::where('group_id', $id)
                    ->select('customers.id as id', 'customers.customer_name as name')->get()
            ];
        } elseif ($grouptype == 3) {
            $data = [
                'obj' => 'provides',
                'results' => Providers::where('group_id', $id)
                    ->select('providers.id as id', 'providers.provider_name as name')->get()
            ];
        }
        // elseif ($grouptype == 4) {
        //     $data = [
        //         'obj' => 'products',
        //         'results' => Products::where('group_id', $id)->where('workspace_id', Auth::user()->current_workspace)
        //             ->select('products.id as id', 'products.product_name as name')->get()
        //     ];
        // }
        // elseif ($grouptype == 5) {
        //     $data = [
        //         'obj' => 'products',
        //         'results' => Products::where('group_id', $id)->where('workspace_id', Auth::user()->current_workspace)
        //             ->select('products.id as id', 'products.product_name as name')->get()
        //     ];
        // }
        return $data;
    }
    public function updateGroup($data, $id)
    {
        return DB::table($this->table)->where('id', $id)->update($data);
    }
    public function updateDataGroup($data)
    {
        DB::table($data['data_obj'])
            ->whereIn('id', $data['dataupdate'])
            ->update(['group_id' => $data['group_id']]);
        $grouptype = Groups::find($data['group_id']);
        $grouptype = $grouptype->grouptype_id;
        $dataRs = [];
        if ($grouptype == 1) {
            $dataRs = [
                'obj' => 'users',
                'results' => User::whereIn('id', $data['dataupdate'])
                    ->select('users.id as id', 'users.name as name')->get()
            ];
        } elseif ($grouptype == 2) {
            $dataRs = [
                'obj' => 'guest',
                'results' => Customers::whereIn('id', $data['dataupdate'])
                    ->select('customers.id as id', 'customers.customer_name as name')->get()
            ];
        } elseif ($grouptype == 3) {
            $dataRs = [
                'obj' => 'provides',
                'results' => Providers::whereIn('id', $data['dataupdate'])
                    ->select('providers.id as id', 'providers.provider_name as name')->get()
            ];
        }
        // elseif ($grouptype == 4) {
        //     $dataRs = [
        //         'obj' => 'products',
        //         'results' => Products::whereIn('id', $data['dataupdate'])
        //             ->select('products.id as id', 'products.product_name as name')->get()
        //     ];
        // }
        return $dataRs;
    }
}
