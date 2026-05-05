<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * Factory tạo dữ liệu người dùng giả (dùng cho testing và seeding).
 * Sử dụng: User::factory()->create() hoặc User::factory(10)->create()
 *
 * @extends Factory<User>
 */
class UserFactory extends Factory
{
    /** Mật khẩu dùng chung cho tất cả user được tạo bởi factory (cache để tăng hiệu suất) */
    protected static ?string $password;

    /**
     * Định nghĩa trạng thái mặc định của model.
     * Tạo user với email ngẫu nhiên, mật khẩu mặc định là 'password'.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name'               => fake()->name(),                          // Tên ngẫu nhiên
            'email'              => fake()->unique()->safeEmail(),           // Email duy nhất
            'email_verified_at'  => now(),                                   // Đã xác minh email
            'password'           => static::$password ??= Hash::make('password'), // Mật khẩu mặc định
            'remember_token'     => Str::random(10),                         // Token ghi nhớ đăng nhập
        ];
    }

    /**
     * Trạng thái email chưa được xác minh.
     * Sử dụng: User::factory()->unverified()->create()
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null, // Chưa xác minh email
        ]);
    }
}
