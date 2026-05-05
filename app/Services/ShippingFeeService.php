<?php

namespace App\Services;

use App\Models\ShippingFee;
use Illuminate\Support\Facades\Log;

/**
 * Service tính toán phí vận chuyển dựa trên giá trị đơn hàng.
 * Áp dụng quy tắc bậc thang: chọn quy tắc có min_order_value cao nhất
 * mà đơn hàng vẫn đạt được.
 *
 * VD: Quy tắc 1: đơn >= 500.000đ → 0đ (miễn phí)
 *     Quy tắc 2: đơn >= 0đ       → 30.000đ
 * → Đơn 600.000đ sẽ áp dụng Quy tắc 1 (miễn phí)
 * → Đơn 200.000đ sẽ áp dụng Quy tắc 2 (30.000đ)
 */
class ShippingFeeService
{
    /**
     * Tính phí vận chuyển cho đơn hàng.
     *
     * Thuật toán: lấy quy tắc đang hoạt động có min_order_value lớn nhất
     * mà vẫn nhỏ hơn hoặc bằng tổng giỏ hàng.
     *
     * @param float $cartTotal Tổng giá trị giỏ hàng (chưa tính phí ship)
     * @return float Phí vận chuyển (đơn vị: VNĐ)
     */
    public function calculate(float $cartTotal): float
    {
        $rule = ShippingFee::where('is_active', true)
            ->where('min_order_value', '<=', $cartTotal)  // Đơn hàng đạt mức tối thiểu
            ->orderBy('min_order_value', 'desc')           // Ưu tiên quy tắc có mức cao nhất
            ->first();

        if (!$rule) {
            // Không có quy tắc nào phù hợp → dùng phí mặc định 30.000đ
            Log::warning('No shipping fee rules found, using default 30,000 VND');
            return 30000;
        }

        return (float) $rule->fee;
    }
}
