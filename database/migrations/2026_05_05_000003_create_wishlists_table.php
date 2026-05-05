<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migration tạo bảng wishlists (danh sách sản phẩm yêu thích).
 * Lưu mối quan hệ nhiều-nhiều giữa users và t_food.
 * Ràng buộc unique(user_id, food_id) đảm bảo không thêm trùng.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('wishlists', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id'); // Khóa ngoại → bảng users
            $table->unsignedBigInteger('food_id'); // Khóa ngoại → bảng t_food
            $table->timestamps();

            // Mỗi người dùng chỉ có thể yêu thích một sản phẩm một lần
            $table->unique(['user_id', 'food_id']);

            // Xóa wishlist khi xóa user hoặc sản phẩm
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('food_id')->references('id')->on('t_food')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('wishlists');
    }
};
