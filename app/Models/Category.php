<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Model danh mục sản phẩm (Category / Loại sản phẩm).
 * Lưu trong bảng 'type_products'.
 * Mỗi danh mục có thể chứa nhiều sản phẩm.
 */
class Category extends Model
{
    use HasFactory;

    /** Tên bảng trong database */
    protected $table = 'type_products';

    protected $fillable = [
        'name',        // Tên danh mục (VD: Áo nam, Quần nữ)
        'slug',        // Slug URL thân thiện (VD: ao-nam)
        'description', // Mô tả danh mục
        'image',       // Đường dẫn ảnh đại diện
        'is_active',   // Trạng thái hiển thị (true/false)
    ];

    protected $casts = [
        'is_active' => 'boolean', // Tự động cast sang boolean
    ];

    /**
     * Quan hệ: một danh mục có nhiều sản phẩm.
     */
    public function foods()
    {
        return $this->hasMany(Food::class, 'category_id');
    }
}
