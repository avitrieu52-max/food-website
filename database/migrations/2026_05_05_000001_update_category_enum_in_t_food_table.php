<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Xóa dữ liệu cũ (category thực phẩm không còn hợp lệ)
        DB::table('t_food')->delete();

        // Thay đổi enum category sang danh mục quần áo
        DB::statement("ALTER TABLE t_food MODIFY COLUMN category ENUM('ao_nam','ao_nu','quan_nam','quan_nu','vay_dam','phu_kien') NOT NULL");
    }

    public function down(): void
    {
        DB::table('t_food')->delete();

        DB::statement("ALTER TABLE t_food MODIFY COLUMN category ENUM('hoa_qua','thuc_pham_huu_co','thuc_pham_kho','san_pham_noi_bat') NOT NULL");
    }
};
