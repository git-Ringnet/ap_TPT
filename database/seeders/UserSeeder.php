<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        // Tạo người dùng mặc định
        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@ringnet.vn',
            'password' => Hash::make('Ringnet@123'),
        ]);
        $admin->assignRole('Admin');

        $warehouseManager = User::create([
            'name' => 'Quản lý kho',
            'email' => 'quankho@ringnet.vn',
            'password' => Hash::make('Ringnet@123'),
        ]);
        $warehouseManager->assignRole('Quản lý kho');

        $serviceUser = User::create([
            'name' => 'Bảo hành',
            'email' => 'baohanh@ringnet.vn',
            'password' => Hash::make('Ringnet@123'),
        ]);
        $serviceUser->assignRole('Bảo hành');
    }
}
