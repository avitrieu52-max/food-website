<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

/**
 * Seeder gốc của ứng dụng.
 * Gọi tất cả các seeder con theo thứ tự.
 * Chạy lệnh: php artisan db:seed
 */
class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents; // Tắt model events khi seed để tăng hiệu suất

    /**
     * Chạy tất cả seeder để khởi tạo dữ liệu mẫu cho ứng dụng.
     */
    public function run(): void
    {
        // Tạo một user test mặc định bằng factory
        User::factory()->create([
            'name'  => 'Test User',
            'email' => 'test@example.com',
        ]);

        // Gọi các seeder con theo thứ tự
        $this->call([
            AdminUserSeeder::class,  // Tạo tài khoản admin/manager
            SlideSeeder::class,      // Tạo slide/banner trang chủ
        ]);
    }
}
