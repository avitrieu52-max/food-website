<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Model quy tắc phí vận chuyển (ShippingFee).
 * Hệ thống tính phí theo bậc thang:
 * đơn hàng đạt giá trị tối thiểu (min_order_value) thì áp dụng mức phí (fee) tương ứng.
 * VD: đơn từ 500.000đ → miễn phí; đơn dưới 500.000đ → 30.000đ.
 */
class ShippingFee extends Model
{
    use HasFactory;

    protected $table = 'shipping_fees';

    protected $fillable = [
        'name',            // Tên quy tắc (VD: "Miễn phí vận chuyển")
        'min_order_value', // Giá trị đơn hàng tối thiểu để áp dụng quy tắc này
        'fee',             // Số tiền phí vận chuyển (0 = miễn phí)
        'is_active',       // Trạng thái kích hoạt
    ];

    protected $casts = [
        'min_order_value' => 'decimal:2',
        'fee'             => 'decimal:2',
        'is_active'       => 'boolean',
    ];
}
