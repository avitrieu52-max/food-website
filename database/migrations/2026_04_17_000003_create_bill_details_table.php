<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migration tạo bảng bill_details (chi tiết đơn hàng).
 * Mỗi dòng lưu một sản phẩm trong đơn hàng:
 * sản phẩm nào, số lượng bao nhiêu, giá tại thời điểm đặt.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bill_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_bill');           // Khóa ngoại → bảng bills
            $table->unsignedBigInteger('id_product');        // Khóa ngoại → bảng t_food
            $table->integer('quantity');                     // Số lượng đặt
            $table->decimal('unit_price', 16, 2);            // Giá đơn vị tại thời điểm đặt (lưu lại để tránh thay đổi giá sau)
            $table->timestamps();

            // Xóa chi tiết khi xóa đơn hàng
            $table->foreign('id_bill')->references('id')->on('bills')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bill_details');
    }
};
