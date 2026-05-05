<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Model mã giảm giá (Coupon).
 * Hỗ trợ 2 loại giảm giá: theo phần trăm (percent) hoặc số tiền cố định (fixed).
 * Có thể giới hạn số lần sử dụng và ngày hết hạn.
 */
class Coupon extends Model
{
    use HasFactory;

    protected $table = 'coupons';

    protected $fillable = [
        'code',            // Mã giảm giá (VD: SUMMER50) - luôn lưu dạng chữ hoa
        'discount_type',   // Loại giảm: 'percent' (%) hoặc 'fixed' (số tiền cố định)
        'discount_value',  // Giá trị giảm (VD: 10 = 10% hoặc 50000 = 50.000đ)
        'min_order_value', // Giá trị đơn hàng tối thiểu để áp dụng mã
        'max_uses',        // Số lần sử dụng tối đa (NULL = không giới hạn)
        'used_count',      // Số lần đã được sử dụng
        'expires_at',      // Ngày hết hạn (NULL = không hết hạn)
        'is_active',       // Trạng thái kích hoạt
    ];

    protected $casts = [
        'discount_value'  => 'decimal:2',
        'min_order_value' => 'decimal:2',
        'expires_at'      => 'datetime', // Tự động cast sang Carbon datetime
        'is_active'       => 'boolean',
    ];
}
