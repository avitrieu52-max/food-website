<?php

namespace App\Services;

use App\Models\Coupon;

/**
 * Service xử lý logic nghiệp vụ liên quan đến mã giảm giá.
 * Tách biệt logic khỏi controller để dễ tái sử dụng và kiểm thử.
 */
class CouponService
{
    /**
     * Kiểm tra tính hợp lệ của mã giảm giá so với tổng giá trị giỏ hàng.
     *
     * Các điều kiện kiểm tra theo thứ tự:
     * 1. Mã có tồn tại không (không phân biệt hoa/thường)
     * 2. Mã có đang được kích hoạt không
     * 3. Mã có hết hạn chưa
     * 4. Mã có còn lượt sử dụng không
     * 5. Giá trị đơn hàng có đạt mức tối thiểu không
     *
     * @param string $code      Mã giảm giá người dùng nhập
     * @param float  $cartTotal Tổng giá trị giỏ hàng
     * @return array{valid: bool, coupon: Coupon|null, error: string|null}
     */
    public function validate(string $code, float $cartTotal): array
    {
        // Tìm mã không phân biệt hoa/thường
        $coupon = Coupon::whereRaw('UPPER(code) = ?', [strtoupper($code)])->first();

        if (!$coupon) {
            return ['valid' => false, 'coupon' => null, 'error' => 'Mã giảm giá không tồn tại.'];
        }

        if (!$coupon->is_active) {
            return ['valid' => false, 'coupon' => null, 'error' => 'Mã giảm giá đã bị vô hiệu hóa.'];
        }

        // Kiểm tra ngày hết hạn (isPast() = đã qua ngày hết hạn)
        if ($coupon->expires_at && $coupon->expires_at->isPast()) {
            return ['valid' => false, 'coupon' => null, 'error' => 'Mã giảm giá đã hết hạn.'];
        }

        // Kiểm tra giới hạn lượt sử dụng (NULL = không giới hạn)
        if ($coupon->max_uses !== null && $coupon->used_count >= $coupon->max_uses) {
            return ['valid' => false, 'coupon' => null, 'error' => 'Mã giảm giá đã hết lượt sử dụng.'];
        }

        // Kiểm tra giá trị đơn hàng tối thiểu
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
     * Tính số tiền được giảm dựa trên loại mã giảm giá và tổng giỏ hàng.
     *
     * - Loại 'percent': giảm theo % của tổng giỏ hàng
     * - Loại 'fixed': giảm một số tiền cố định
     *
     * Kết quả được giới hạn tối đa bằng tổng giỏ hàng
     * (đảm bảo tổng tiền không bao giờ âm).
     *
     * @param Coupon $coupon    Đối tượng mã giảm giá đã được xác thực
     * @param float  $cartTotal Tổng giá trị giỏ hàng
     * @return float Số tiền được giảm
     */
    public function calculateDiscount(Coupon $coupon, float $cartTotal): float
    {
        if ($coupon->discount_type === 'percent') {
            // Giảm theo phần trăm
            $discount = $cartTotal * (float) $coupon->discount_value / 100;
        } else {
            // Giảm số tiền cố định
            $discount = (float) $coupon->discount_value;
        }

        // Giới hạn không vượt quá tổng giỏ hàng
        return min($discount, $cartTotal);
    }
}
