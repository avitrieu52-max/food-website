<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Migration thống nhất hệ thống danh mục sản phẩm.
 * Chuyển từ cột enum cứng (category) sang khóa ngoại động (category_id → type_products).
 *
 * Các bước thực hiện:
 * 1. Thêm slug và is_active vào bảng type_products
 * 2. Xóa dữ liệu cũ và seed 6 danh mục quần áo
 * 3. Thêm cột category_id vào t_food
 * 4. Map giá trị enum cũ sang category_id mới
 * 5. Đặt category_id NOT NULL và thêm khóa ngoại
 * 6. Xóa cột enum cũ
 */
return new class extends Migration
{
    public function up(): void
    {
        // Bước 1: Thêm slug và is_active vào bảng type_products
        Schema::table('type_products', function (Blueprint $table) {
            $table->string('slug', 100)->nullable()->after('name');
            $table->boolean('is_active')->default(true)->after('image');
        });

        // Bước 2: Xóa dữ liệu cũ và seed 6 danh mục quần áo
        DB::table('type_products')->truncate();

        $categories = [
            ['name' => 'Áo nam',    'slug' => 'ao_nam'],
            ['name' => 'Áo nữ',     'slug' => 'ao_nu'],
            ['name' => 'Quần nam',  'slug' => 'quan_nam'],
            ['name' => 'Quần nữ',   'slug' => 'quan_nu'],
            ['name' => 'Váy & Đầm', 'slug' => 'vay_dam'],
            ['name' => 'Phụ kiện',  'slug' => 'phu_kien'],
        ];

        foreach ($categories as $cat) {
            DB::table('type_products')->insert([
                'name'       => $cat['name'],
                'slug'       => $cat['slug'],
                'is_active'  => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Bước 3: Thêm cột category_id vào t_food (nullable trước để map dữ liệu)
        Schema::table('t_food', function (Blueprint $table) {
            $table->unsignedBigInteger('category_id')->nullable()->after('category');
        });

        // Bước 4: Map giá trị enum cũ (slug) sang category_id mới
        $map = DB::table('type_products')->pluck('id', 'slug');
        foreach ($map as $slug => $id) {
            DB::table('t_food')->where('category', $slug)->update(['category_id' => $id]);
        }

        // Bước 5: Đặt category_id NOT NULL và thêm khóa ngoại
        $firstId = DB::table('type_products')->value('id');
        DB::table('t_food')->whereNull('category_id')->update(['category_id' => $firstId]);

        Schema::table('t_food', function (Blueprint $table) {
            $table->unsignedBigInteger('category_id')->nullable(false)->change();
            $table->foreign('category_id')->references('id')->on('type_products')->onDelete('restrict');
        });

        // Bước 6: Xóa cột enum cũ (không còn cần thiết)
        Schema::table('t_food', function (Blueprint $table) {
            $table->dropColumn('category');
        });
    }

    public function down(): void
    {
        // Khôi phục cột enum cũ và xóa category_id
        Schema::table('t_food', function (Blueprint $table) {
            $table->dropForeign(['category_id']);
            $table->dropColumn('category_id');
            $table->enum('category', ['ao_nam','ao_nu','quan_nam','quan_nu','vay_dam','phu_kien'])->default('ao_nam');
        });

        Schema::table('type_products', function (Blueprint $table) {
            $table->dropColumn(['slug', 'is_active']);
        });
    }
};
