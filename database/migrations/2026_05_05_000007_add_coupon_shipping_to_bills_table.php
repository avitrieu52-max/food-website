<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migration thêm các cột liên quan đến mã giảm giá và phí vận chuyển vào bảng bills.
 * Thêm: coupon_code, discount_amount, shipping_fee.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bills', function (Blueprint $table) {
            $table->string('coupon_code', 50)->nullable()->after('status');          // Mã giảm giá đã áp dụng
            $table->decimal('discount_amount', 16, 2)->default(0)->after('coupon_code'); // Số tiền được giảm
            $table->decimal('shipping_fee', 16, 2)->default(0)->after('discount_amount'); // Phí vận chuyển
        });
    }

    public function down(): void
    {
        Schema::table('bills', function (Blueprint $table) {
            $table->dropColumn(['coupon_code', 'discount_amount', 'shipping_fee']);
        });
    }
};
