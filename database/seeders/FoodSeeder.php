<?php

namespace Database\Seeders;

use App\Models\Food;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class FoodSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();

        $categories = [
            'hoa_qua',
            'thuc_pham_huu_co',
            'thuc_pham_kho',
            'san_pham_noi_bat'
        ];

        $foodNames = [
            'hoa_qua' => ['Táo đỏ', 'Cam sành', 'Xoài cát', 'Nho Mỹ', 'Dưa hấu'],
            'thuc_pham_huu_co' => ['Rau cải hữu cơ', 'Cà chua hữu cơ', 'Dưa chuột hữu cơ'],
            'thuc_pham_kho' => ['Mực khô', 'Tôm khô', 'Nấm hương khô', 'Miến dong'],
            'san_pham_noi_bat' => ['Bưởi da xanh', 'Sầu riêng', 'Măng cụt', 'Chôm chôm']
        ];

        for ($i = 0; $i < 10; $i++) {
            $category = $faker->randomElement($categories);
            $names = $foodNames[$category] ?? ['Sản phẩm ' . ($i + 1)];
            
            Food::create([
                'name' => $faker->randomElement($names) . ' ' . ($i + 1),
                'slug' => $faker->unique()->slug(2),
                'description' => $faker->paragraph(3),
                'price' => $faker->randomFloat(2, 20000, 500000),
                'sale_price' => $faker->optional(0.5)->randomFloat(2, 15000, 400000),
                'image' => 'images/placeholder.svg',
                'category' => $category,
                'stock' => $faker->numberBetween(0, 100),
                'is_featured' => $faker->boolean(30),
                'status' => true,
            ]);
        }
    }
}