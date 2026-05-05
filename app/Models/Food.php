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
        'image', 'category', 'stock', 'is_featured', 'status'
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'sale_price' => 'decimal:2',
        'is_featured' => 'boolean',
        'status' => 'boolean'
    ];

    // Category labels
    public static function getCategories()
    {
        return [
            'ao_nam'    => 'Áo nam',
            'ao_nu'     => 'Áo nữ',
            'quan_nam'  => 'Quần nam',
            'quan_nu'   => 'Quần nữ',
            'vay_dam'   => 'Váy & Đầm',
            'phu_kien'  => 'Phụ kiện',
        ];
    }

    public function getCategoryLabelAttribute()
    {
        return self::getCategories()[$this->category] ?? $this->category;
    }

    public function getImageUrlAttribute()
    {
        if (! $this->image) {
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

    public function wishlists()
    {
        return $this->hasMany(Wishlist::class, 'food_id');
    }
}