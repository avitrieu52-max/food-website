<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Food extends Model
{
    use HasFactory;

    protected $table = 't_food';

    protected $fillable = [
        'name', 'slug', 'description', 'price', 'sale_price',
        'image', 'category_id', 'stock', 'is_featured', 'status',
    ];

    protected $casts = [
        'price'       => 'decimal:2',
        'sale_price'  => 'decimal:2',
        'is_featured' => 'boolean',
        'status'      => 'boolean',
    ];

    // Relationship
    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function wishlists()
    {
        return $this->hasMany(Wishlist::class, 'food_id');
    }

    // Lấy tất cả danh mục từ DB (thay thế enum cứng)
    public static function getCategories(): array
    {
        return Category::where('is_active', true)
            ->orderBy('id')
            ->pluck('name', 'id')
            ->toArray();
    }

    // Tương thích ngược: trả về tên danh mục
    public function getCategoryLabelAttribute(): string
    {
        return $this->category?->name ?? '';
    }

    public function getImageUrlAttribute(): string
    {
        if (!$this->image) {
            return asset('images/placeholder.svg');
        }
        if (preg_match('/^https?:\/\//i', $this->image)) {
            return $this->image;
        }
        return asset($this->image);
    }

    public function getFinalPriceAttribute()
    {
        return $this->sale_price && $this->sale_price > 0
            ? $this->sale_price
            : $this->price;
    }
}
