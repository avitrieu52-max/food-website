<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Thêm slug vào type_products
        Schema::table('type_products', function (Blueprint $table) {
            $table->string('slug', 100)->nullable()->after('name');
            $table->boolean('is_active')->default(true)->after('image');
        });

        // 2. Xóa dữ liệu cũ không liên quan
        DB::table('type_products')->truncate();

        // 3. Seed 6 danh mục quần áo
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

        // 4. Thêm category_id vào t_food (nullable trước)
        Schema::table('t_food', function (Blueprint $table) {
            $table->unsignedBigInteger('category_id')->nullable()->after('category');
        });

        // 5. Map enum cũ → category_id mới
        $map = DB::table('type_products')->pluck('id', 'slug');
        foreach ($map as $slug => $id) {
            DB::table('t_food')->where('category', $slug)->update(['category_id' => $id]);
        }

        // 6. Đặt category_id NOT NULL với default = category đầu tiên
        $firstId = DB::table('type_products')->value('id');
        DB::table('t_food')->whereNull('category_id')->update(['category_id' => $firstId]);

        Schema::table('t_food', function (Blueprint $table) {
            $table->unsignedBigInteger('category_id')->nullable(false)->change();
            $table->foreign('category_id')->references('id')->on('type_products')->onDelete('restrict');
        });

        // 7. Xóa cột enum cũ
        Schema::table('t_food', function (Blueprint $table) {
            $table->dropColumn('category');
        });
    }

    public function down(): void
    {
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
