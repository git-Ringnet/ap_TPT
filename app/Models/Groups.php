<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

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
        return $this->belongsTo(GroupType::class, 'group_type_id', 'id');
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
        $existingGroup = Groups::where('group_name', $data['group_name_display'])
            ->where('group_type_id', $data['grouptype_id'])
            ->first();

        if ($existingGroup) {
            return true;
        }
        $datagroup = [
            'group_code' => $data['group_code'],
            'group_name' => $data['group_name_display'],
            'group_type_id' => $data['grouptype_id'],
            'description' => $data['group_desc'],
            'created_at' => now(),
        ];

        DB::table($this->table)->insertGetId($datagroup);

        return false;
    }
}
