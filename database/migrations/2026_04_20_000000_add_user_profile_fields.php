<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migration thêm các trường thông tin cá nhân vào bảng users.
 * Thêm: phone (SĐT), address (địa chỉ), level (cấp độ quyền).
 * Kiểm tra tồn tại trước khi thêm để tránh lỗi khi chạy lại.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Thêm số điện thoại nếu chưa có
            if (!Schema::hasColumn('users', 'phone')) {
                $table->string('phone')->nullable()->after('password');
            }
            // Thêm địa chỉ nếu chưa có
            if (!Schema::hasColumn('users', 'address')) {
                $table->text('address')->nullable()->after('phone');
            }
            // Thêm cấp độ quyền nếu chưa có (1=Admin, 2=Manager, 3=Khách hàng)
            if (!Schema::hasColumn('users', 'level')) {
                $table->tinyInteger('level')->default(3)->after('address');
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Xóa các cột đã thêm khi rollback
            if (Schema::hasColumn('users', 'level')) {
                $table->dropColumn('level');
            }
            if (Schema::hasColumn('users', 'address')) {
                $table->dropColumn('address');
            }
            if (Schema::hasColumn('users', 'phone')) {
                $table->dropColumn('phone');
            }
        });
    }
};
