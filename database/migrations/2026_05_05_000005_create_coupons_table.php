<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migration tạo bảng coupons (mã giảm giá).
 * Hỗ trợ 2 loại: giảm theo % (percent) hoặc số tiền cố định (fixed).
 * Có thể giới hạn số lần sử dụng và ngày hết hạn.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('coupons', function (Blueprint $table) {
            $table->id();
            $table->string('code', 50)->unique();                    // Mã giảm giá (duy nhất, lưu chữ hoa)
            $table->enum('discount_type', ['percent', 'fixed']);     // Loại giảm: % hoặc số tiền cố định
            $table->decimal('discount_value', 10, 2);                // Giá trị giảm
            $table->decimal('min_order_value', 16, 2)->default(0);   // Đơn hàng tối thiểu để áp dụng
            $table->unsignedInteger('max_uses')->nullable()->comment('NULL = unlimited'); // Giới hạn lượt dùng
            $table->unsignedInteger('used_count')->default(0);       // Số lần đã sử dụng
            $table->timestamp('expires_at')->nullable();             // Ngày hết hạn (NULL = không hết hạn)
            $table->boolean('is_active')->default(true);             // Trạng thái kích hoạt
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('coupons');
    }
};
