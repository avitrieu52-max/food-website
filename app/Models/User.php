<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

/**
 * Model người dùng hệ thống (User).
 * Dùng cho cả admin, manager và khách hàng đăng ký tài khoản.
 * Phân quyền theo trường 'level': 1=Admin, 2=Manager, 3=Khách hàng.
 */
#[Fillable(['name', 'email', 'password', 'phone', 'address', 'level'])]
#[Hidden(['password', 'remember_token'])] // Ẩn các trường nhạy cảm khi serialize
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Định nghĩa kiểu dữ liệu cho các trường đặc biệt.
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime', // Tự động cast sang Carbon datetime
        ];
    }

    /**
     * Accessor: lấy họ tên đầy đủ của người dùng.
     * Alias của trường 'name' để tương thích với code cũ.
     * Sử dụng: $user->full_name
     */
    public function getFullNameAttribute(): ?string
    {
        return $this->attributes['name'] ?? null;
    }

    /**
     * Mutator: đặt họ tên đầy đủ (ghi vào trường 'name').
     */
    public function setFullNameAttribute(string $value): void
    {
        $this->attributes['name'] = $value;
    }

    /**
     * Quan hệ: người dùng có nhiều sản phẩm yêu thích.
     */
    public function wishlists()
    {
        return $this->hasMany(Wishlist::class);
    }

    /**
     * Lấy danh sách ID các sản phẩm trong wishlist của người dùng.
     * Dùng để kiểm tra nhanh sản phẩm nào đã được yêu thích.
     *
     * @return array Mảng các food_id
     */
    public function wishlistFoodIds(): array
    {
        return $this->wishlists()->pluck('food_id')->toArray();
    }
}
