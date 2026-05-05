<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migration tạo các bảng cơ bản của hệ thống xác thực Laravel:
 * - users: tài khoản người dùng (admin, manager, khách hàng)
 * - password_reset_tokens: token đặt lại mật khẩu
 * - sessions: lưu phiên đăng nhập (SESSION_DRIVER=database)
 */
return new class extends Migration
{
    /**
     * Tạo các bảng.
     */
    public function up(): void
    {
        // Bảng người dùng hệ thống
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');                          // Họ tên
            $table->string('email')->unique();               // Email đăng nhập (duy nhất)
            $table->timestamp('email_verified_at')->nullable(); // Thời điểm xác minh email
            $table->string('password');                      // Mật khẩu đã mã hóa (bcrypt)
            $table->rememberToken();                         // Token ghi nhớ đăng nhập
            $table->timestamps();
        });

        // Bảng token đặt lại mật khẩu
        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary(); // Email là khóa chính
            $table->string('token');            // Token ngẫu nhiên
            $table->timestamp('created_at')->nullable();
        });

        // Bảng lưu session đăng nhập (dùng khi SESSION_DRIVER=database)
        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();              // Session ID
            $table->foreignId('user_id')->nullable()->index(); // Liên kết user (null nếu chưa đăng nhập)
            $table->string('ip_address', 45)->nullable(); // Địa chỉ IP
            $table->text('user_agent')->nullable();       // Thông tin trình duyệt
            $table->longText('payload');                  // Dữ liệu session (serialized)
            $table->integer('last_activity')->index();    // Thời điểm hoạt động cuối
        });
    }

    /**
     * Xóa các bảng khi rollback migration.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
