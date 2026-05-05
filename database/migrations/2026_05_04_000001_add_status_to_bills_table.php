<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migration thêm cột status vào bảng bills (đơn hàng).
 * Trạng thái đơn hàng: pending → confirmed → shipping → delivered / cancelled.
 * Mặc định là 'pending' (chờ xác nhận) khi tạo đơn mới.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bills', function (Blueprint $table) {
            // Thêm cột status sau cột note, mặc định là 'pending'
            $table->string('status', 20)->default('pending')->after('note');
        });
    }

    public function down(): void
    {
        Schema::table('bills', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }
};
