<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

/**
 * Seeder tạo tài khoản admin và manager mặc định.
 * Chạy lệnh: php artisan db:seed --class=AdminUserSeeder
 *
 * Tài khoản được tạo:
 * - Admin:    admin@example.com    / password (level 1)
 * - Manager:  manager@example.com  / password (level 2)
 * - Customer: customer@example.com / password (level 3)
 */
class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        // Tạo tài khoản Admin (level 1 - toàn quyền quản trị)
        User::create([
            'name'     => 'Admin',
            'email'    => 'admin@example.com',
            'password' => Hash::make('password'),
            'phone'    => '0123456789',
            'address'  => 'Admin Address',
            'level'    => 1,
        ]);

        // Tạo tài khoản Manager (level 2 - quản lý)
        User::create([
            'name'     => 'Manager',
            'email'    => 'manager@example.com',
            'password' => Hash::make('password'),
            'phone'    => '0987654321',
            'address'  => 'Manager Address',
            'level'    => 2,
        ]);

        // Tạo tài khoản khách hàng mẫu (level 3 - khách hàng)
        User::create([
            'name'     => 'Customer',
            'email'    => 'customer@example.com',
            'password' => Hash::make('password'),
            'phone'    => '0123456789',
            'address'  => 'Customer Address',
            'level'    => 3,
        ]);
    }
}
