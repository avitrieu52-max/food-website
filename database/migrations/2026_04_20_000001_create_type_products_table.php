<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migration tạo bảng type_products (danh mục sản phẩm).
 * Bảng này được dùng bởi model Category.
 * Sau này được mở rộng thêm slug và is_active trong migration unify_category_system.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('type_products', function (Blueprint $table) {
            $table->id();
            $table->string('name');                  // Tên danh mục (VD: Áo nam, Quần nữ)
            $table->text('description')->nullable(); // Mô tả danh mục
            $table->string('image')->nullable();     // Ảnh đại diện danh mục
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('type_products');
    }
};
