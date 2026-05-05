<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('shipping_fees', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->decimal('min_order_value', 16, 2)->default(0);
            $table->decimal('fee', 10, 2)->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Seed default rules
        DB::table('shipping_fees')->insert([
            ['name' => 'Miễn phí vận chuyển (đơn từ 500.000đ)', 'min_order_value' => 500000, 'fee' => 0,     'is_active' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Phí vận chuyển tiêu chuẩn',              'min_order_value' => 0,      'fee' => 30000, 'is_active' => 1, 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('shipping_fees');
    }
};
