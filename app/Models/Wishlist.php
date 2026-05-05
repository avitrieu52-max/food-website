<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Model danh sách sản phẩm yêu thích (Wishlist).
 * Lưu mối quan hệ nhiều-nhiều giữa người dùng và sản phẩm.
 * Mỗi cặp (user_id, food_id) là duy nhất (không thêm trùng).
 */
class Wishlist extends Model
{
    use HasFactory;

    protected $table = 'wishlists';

    protected $fillable = [
        'user_id', // ID người dùng (liên kết bảng users)
        'food_id', // ID sản phẩm (liên kết bảng t_food)
    ];

    /**
     * Quan hệ: wishlist thuộc về một người dùng.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Quan hệ: wishlist liên kết với một sản phẩm.
     */
    public function food()
    {
        return $this->belongsTo(Food::class, 'food_id');
    }
}
