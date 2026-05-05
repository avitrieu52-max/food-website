<?php

namespace App\Services;

use App\Models\ShippingFee;
use Illuminate\Support\Facades\Log;

class ShippingFeeService
{
    /**
     * Calculate shipping fee based on cart total.
     * Selects the rule with the highest min_order_value that cart_total satisfies.
     */
    public function calculate(float $cartTotal): float
    {
        $rule = ShippingFee::where('is_active', true)
            ->where('min_order_value', '<=', $cartTotal)
            ->orderBy('min_order_value', 'desc')
            ->first();

        if (!$rule) {
            // Fallback if no rules configured
            Log::warning('No shipping fee rules found, using default 30,000 VND');
            return 30000;
        }

        return (float) $rule->fee;
    }
}
