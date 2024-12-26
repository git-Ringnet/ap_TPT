<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'group_id',
        'employee_code',
        'role',
        'address',
        'phone',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
    public function getAllUsers($data = null)
    {
        $users = DB::table('users');

        // Tìm kiếm chung
        if (!empty($data['search'])) {
            $users->where(function ($query) use ($data) {
                $query->where('employee_code', 'like', '%' . $data['search'] . '%')
                    ->orWhere('name', 'like', '%' . $data['search'] . '%')
                    ->orWhere('address', 'like', '%' . $data['search'] . '%')
                    ->orWhere('phone', 'like', '%' . $data['search'] . '%')
                    ->orWhere('email', 'like', '%' . $data['search'] . '%');
            });
        }
        // Lọc theo các trường cụ thể
        $filterableFields = [
            'ma' => 'employee_code',
            'ten' => 'name',
            'address' => 'address',
            'phone' => 'phone',
            'email' => 'email',
            'note' => 'note',
        ];
        foreach ($filterableFields as $key => $field) {
            if (!empty($data[$key])) {
                $users->where($field, 'like', '%' . $data[$key] . '%');
            }
        }
        if (isset($data['roles']) && is_array($data['roles']) && !empty($data['roles'])) {
            $users = $users->whereIn('role', $data['roles']);
        }
        return $users->get();
    }
}
