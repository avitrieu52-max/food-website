<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migration tạo bảng customers (thông tin khách hàng đặt hàng).
 * Khác với bảng users (tài khoản đăng nhập), bảng này lưu thông tin
 * giao hàng được nhập khi checkout. Mỗi đơn hàng tạo một bản ghi mới.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->string('name');                  // Họ tên người nhận hàng
            $table->string('gender')->nullable();    // Giới tính (nam/nữ)
            $table->string('email');                 // Email nhận thông báo đơn hàng
            $table->string('address');               // Địa chỉ giao hàng
            $table->string('phone_number');          // Số điện thoại liên hệ
            $table->text('note')->nullable();        // Ghi chú thêm
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};
