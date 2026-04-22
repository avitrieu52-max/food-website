<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'phone' => '0123456789',
            'address' => 'Admin Address',
            'level' => 1, // Admin level
        ]);

        User::create([
            'name' => 'Manager',
            'email' => 'manager@example.com',
            'password' => Hash::make('password'),
            'phone' => '0987654321',
            'address' => 'Manager Address',
            'level' => 2, // Manager level
        ]);

        User::create([
            'name' => 'Customer',
            'email' => 'customer@example.com',
            'password' => Hash::make('password'),
            'phone' => '0123456789',
            'address' => 'Customer Address',
            'level' => 3, // Customer level
        ]);
    }
}