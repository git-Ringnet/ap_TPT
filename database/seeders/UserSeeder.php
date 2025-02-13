<?php

namespace Database\Seeders;

use App\Models\Customers;
use App\Models\Product;
use App\Models\Providers;
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
            'email' => 'admin@thienphattien.com',
            'password' => Hash::make('Admin@123'),
        ]);
        $admin->assignRole('Admin');

        $warehouseManager = User::create([
            'name' => 'Quản lý kho',
            'email' => 'quankho@thienphattien.com',
            'password' => Hash::make('Quankho@123'),
        ]);
        $warehouseManager->assignRole('Quản lý kho');

        $serviceUser = User::create([
            'name' => 'Bảo hành',
            'email' => 'baohanh@thienphattien.com',
            'password' => Hash::make('Baohanh@123'),
        ]);
        $serviceUser->assignRole('Bảo hành');

        Customers::create([
            'customer_code' => 'kh1',
            'customer_name' => 'kh1',
        ]);
        Providers::create([
            'provider_code' => 'ncc1',
            'provider_name' => 'ncc1',
        ]);
        Product::insert([
            [
                'product_code' => 'sp1',
                'product_name' => 'sp1',
            ],
            [
                'product_code' => 'sp2',
                'product_name' => 'sp2',
            ],
        ]);
    }
}
