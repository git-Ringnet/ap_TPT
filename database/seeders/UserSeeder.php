<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        // Tạo người dùng mặc định
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@ringnet.vn',
            'password' => Hash::make('Ringnet@123'),
        ]);
        User::create([
            'name' => 'Quản lý kho',
            'email' => 'quankho@ringnet.vn',
            'password' => Hash::make('Ringnet@123'),
        ]);
        User::create([
            'name' => 'Dịch vụ',
            'email' => 'dichvu@ringnet.vn',
            'password' => Hash::make('Ringnet@123'),
        ]);
    }
}
