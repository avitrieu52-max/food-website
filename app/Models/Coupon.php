<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Coupon extends Model
{
    use HasFactory;

    protected $table = 'coupons';

    protected $fillable = [
        'code', 'discount_type', 'discount_value', 'min_order_value',
        'max_uses', 'used_count', 'expires_at', 'is_active',
    ];

    protected $casts = [
        'discount_value'  => 'decimal:2',
        'min_order_value' => 'decimal:2',
        'expires_at'      => 'datetime',
        'is_active'       => 'boolean',
    ];
}
