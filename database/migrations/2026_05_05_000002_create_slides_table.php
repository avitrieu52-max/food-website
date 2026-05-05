<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migration tạo bảng slides (banner/slide trang chủ).
 * Mỗi slide hiển thị một ảnh lớn với tiêu đề, mô tả và nút CTA.
 * Thứ tự hiển thị được kiểm soát bởi cột 'order'.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('slides', function (Blueprint $table) {
            $table->id();
            $table->string('title', 255);                          // Tiêu đề chính
            $table->string('subtitle', 255)->nullable();           // Tiêu đề phụ (VD: "NEW COLLECTION")
            $table->text('description')->nullable();               // Mô tả ngắn
            $table->string('image', 500);                          // Đường dẫn ảnh nền
            $table->string('link', 500)->nullable();               // URL khi click nút CTA
            $table->string('button_text', 100)->default('Xem ngay'); // Nội dung nút CTA
            $table->tinyInteger('order')->unsigned()->default(0);  // Thứ tự hiển thị (nhỏ hơn = trước)
            $table->boolean('is_active')->default(true);           // Trạng thái hiển thị
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('slides');
    }
};
