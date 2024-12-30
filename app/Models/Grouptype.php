<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Grouptype extends Model
{
    use HasFactory;
    protected $table = 'group_types';
    public function groups()
    {
        return $this->hasMany(Groups::class, 'group_type_id', 'id');
    }
}
