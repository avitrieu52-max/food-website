<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

/**
 * Migration tạo bảng shipping_fees (quy tắc phí vận chuyển).
 * Phí được tính theo bậc thang dựa trên giá trị đơn hàng.
 * Tự động seed 2 quy tắc mặc định khi tạo bảng.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('shipping_fees', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);                           // Tên quy tắc (VD: "Miễn phí vận chuyển")
            $table->decimal('min_order_value', 16, 2)->default(0); // Giá trị đơn hàng tối thiểu để áp dụng
            $table->decimal('fee', 10, 2)->default(0);             // Phí vận chuyển (0 = miễn phí)
            $table->boolean('is_active')->default(true);           // Trạng thái kích hoạt
            $table->timestamps();
        });

        // Seed 2 quy tắc mặc định ngay khi tạo bảng
        DB::table('shipping_fees')->insert([
            // Quy tắc 1: Miễn phí cho đơn từ 500.000đ
            ['name' => 'Miễn phí vận chuyển (đơn từ 500.000đ)', 'min_order_value' => 500000, 'fee' => 0,     'is_active' => 1, 'created_at' => now(), 'updated_at' => now()],
            // Quy tắc 2: Phí tiêu chuẩn 30.000đ cho đơn dưới 500.000đ
            ['name' => 'Phí vận chuyển tiêu chuẩn',              'min_order_value' => 0,      'fee' => 30000, 'is_active' => 1, 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('shipping_fees');
    }
};
