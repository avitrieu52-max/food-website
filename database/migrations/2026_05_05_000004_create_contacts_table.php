<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migration tạo bảng contacts (liên hệ từ khách hàng).
 * Lưu thông tin khách hàng gửi form liên hệ trên trang chủ.
 * Admin có thể xem và gửi email phản hồi từ trang quản trị.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('contacts', function (Blueprint $table) {
            $table->id();
            $table->string('name', 255);                              // Họ tên người liên hệ
            $table->string('email', 255);                             // Email để phản hồi
            $table->text('message');                                  // Nội dung liên hệ
            $table->enum('status', ['unread', 'replied'])->default('unread'); // Trạng thái xử lý
            $table->timestamp('replied_at')->nullable();              // Thời điểm admin phản hồi
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('contacts');
    }
};
