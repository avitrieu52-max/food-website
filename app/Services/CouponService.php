<?php

namespace App\Services;

use App\Models\Coupon;

class CouponService
{
    /**
     * Validate a coupon code against the cart total.
     *
     * @return array{valid: bool, coupon: Coupon|null, error: string|null}
     */
    public function validate(string $code, float $cartTotal): array
    {
        $coupon = Coupon::whereRaw('UPPER(code) = ?', [strtoupper($code)])->first();

        if (!$coupon) {
            return ['valid' => false, 'coupon' => null, 'error' => 'Mã giảm giá không tồn tại.'];
        }

        if (!$coupon->is_active) {
            return ['valid' => false, 'coupon' => null, 'error' => 'Mã giảm giá đã bị vô hiệu hóa.'];
        }

        if ($coupon->expires_at && $coupon->expires_at->isPast()) {
            return ['valid' => false, 'coupon' => null, 'error' => 'Mã giảm giá đã hết hạn.'];
        }

        if ($coupon->max_uses !== null && $coupon->used_count >= $coupon->max_uses) {
            return ['valid' => false, 'coupon' => null, 'error' => 'Mã giảm giá đã hết lượt sử dụng.'];
        }

        if ($cartTotal < (float) $coupon->min_order_value) {
            return [
                'valid'  => false,
                'coupon' => null,
                'error'  => 'Đơn hàng tối thiểu ' . number_format($coupon->min_order_value) . 'đ để dùng mã này.',
            ];
        }

        return ['valid' => true, 'coupon' => $coupon, 'error' => null];
    }

    /**
     * Calculate the discount amount for a given coupon and cart total.
     * Result is always capped at cart total (never negative final price).
     */
    public function calculateDiscount(Coupon $coupon, float $cartTotal): float
    {
        if ($coupon->discount_type === 'percent') {
            $discount = $cartTotal * (float) $coupon->discount_value / 100;
        } else {
            $discount = (float) $coupon->discount_value;
        }

        return min($discount, $cartTotal);
    }
}
