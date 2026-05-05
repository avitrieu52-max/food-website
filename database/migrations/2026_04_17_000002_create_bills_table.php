<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migration tạo bảng bills (đơn hàng).
 * Lưu thông tin tổng quan của mỗi đơn hàng:
 * khách hàng, ngày đặt, tổng tiền, phương thức thanh toán.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bills', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_customer');       // Khóa ngoại → bảng customers
            $table->date('date_order');                      // Ngày đặt hàng
            $table->decimal('total', 16, 2);                 // Tổng tiền cuối (đã tính phí ship, giảm giá)
            $table->string('payment');                       // Phương thức thanh toán (COD, chuyển khoản...)
            $table->text('note')->nullable();                // Ghi chú của khách
            $table->timestamps();

            // Ràng buộc khóa ngoại: xóa đơn hàng khi xóa khách hàng
            $table->foreign('id_customer')->references('id')->on('customers')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bills');
    }
};
