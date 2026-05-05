<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Model sản phẩm (Food/Thời trang).
 * Lưu trong bảng 't_food'. Tên "Food" giữ nguyên từ cấu trúc ban đầu của dự án.
 * Mỗi sản phẩm thuộc một danh mục và có thể có giá khuyến mãi.
 */
class Food extends Model
{
    use HasFactory;

    /** Tên bảng trong database */
    protected $table = 't_food';

    protected $fillable = [
        'name',        // Tên sản phẩm
        'slug',        // Slug URL thân thiện (duy nhất)
        'description', // Mô tả sản phẩm
        'price',       // Giá gốc
        'sale_price',  // Giá khuyến mãi (NULL nếu không có KM)
        'image',       // Đường dẫn ảnh sản phẩm
        'category_id', // ID danh mục (liên kết bảng type_products)
        'stock',       // Số lượng tồn kho
        'is_featured', // Sản phẩm nổi bật / đề nghị (true/false)
        'status',      // Trạng thái hiển thị (true = đang bán)
    ];

    protected $casts = [
        'price'       => 'decimal:2',
        'sale_price'  => 'decimal:2',
        'is_featured' => 'boolean',
        'status'      => 'boolean',
    ];

    /**
     * Quan hệ: sản phẩm thuộc về một danh mục.
     */
    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    /**
     * Quan hệ: sản phẩm có thể được nhiều người dùng yêu thích.
     */
    public function wishlists()
    {
        return $this->hasMany(Wishlist::class, 'food_id');
    }

    /**
     * Lấy tất cả danh mục đang hoạt động từ database.
     * Trả về mảng [id => name] để dùng trong dropdown.
     */
    public static function getCategories(): array
    {
        return Category::where('is_active', true)
            ->orderBy('id')
            ->pluck('name', 'id')
            ->toArray();
    }

    /**
     * Accessor: lấy tên danh mục của sản phẩm.
     * Sử dụng: $food->category_label
     */
    public function getCategoryLabelAttribute(): string
    {
        return $this->category?->name ?? '';
    }

    /**
     * Accessor: lấy URL đầy đủ của ảnh sản phẩm.
     * Xử lý 3 trường hợp: không có ảnh, URL tuyệt đối, đường dẫn tương đối.
     * Sử dụng: $food->image_url
     */
    public function getImageUrlAttribute(): string
    {
        if (!$this->image) {
            return asset('images/placeholder.svg'); // Ảnh mặc định nếu không có
        }
        if (preg_match('/^https?:\/\//i', $this->image)) {
            return $this->image; // URL tuyệt đối (ảnh từ internet)
        }
        return asset($this->image); // Đường dẫn tương đối trong public/
    }

    /**
     * Accessor: lấy giá hiển thị cuối cùng của sản phẩm.
     * Ưu tiên giá khuyến mãi nếu có, ngược lại dùng giá gốc.
     * Sử dụng: $food->final_price
     */
    public function getFinalPriceAttribute()
    {
        return $this->sale_price && $this->sale_price > 0
            ? $this->sale_price
            : $this->price;
    }
}
