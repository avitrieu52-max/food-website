<?php

namespace Database\Seeders;

use App\Models\Food;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class FoodSeeder extends Seeder
{
    public function run(): void
    {
        $products = [
            // Áo nam
            ['name' => 'Áo sơ mi nam trắng basic', 'category' => 'ao_nam', 'price' => 299000, 'sale_price' => 249000, 'is_featured' => true, 'description' => 'Áo sơ mi nam chất liệu cotton 100%, form regular fit, phù hợp đi làm và dạo phố.'],
            ['name' => 'Áo thun nam cổ tròn đen', 'category' => 'ao_nam', 'price' => 199000, 'sale_price' => null, 'is_featured' => false, 'description' => 'Áo thun nam cổ tròn chất liệu cotton co giãn 4 chiều, thoáng mát.'],
            ['name' => 'Áo polo nam kẻ sọc', 'category' => 'ao_nam', 'price' => 350000, 'sale_price' => 299000, 'is_featured' => true, 'description' => 'Áo polo nam kẻ sọc ngang, chất liệu pique cotton cao cấp, form slim fit.'],
            ['name' => 'Áo khoác nam bomber xanh navy', 'category' => 'ao_nam', 'price' => 650000, 'sale_price' => 550000, 'is_featured' => false, 'description' => 'Áo khoác bomber nam chất liệu dù cao cấp, lót bông ấm áp, phong cách streetwear.'],

            // Áo nữ
            ['name' => 'Áo blouse nữ trắng cổ V', 'category' => 'ao_nu', 'price' => 280000, 'sale_price' => 230000, 'is_featured' => true, 'description' => 'Áo blouse nữ cổ V thanh lịch, chất liệu voan mềm mại, phù hợp công sở.'],
            ['name' => 'Áo thun nữ crop top hồng pastel', 'category' => 'ao_nu', 'price' => 180000, 'sale_price' => null, 'is_featured' => false, 'description' => 'Áo thun nữ crop top màu hồng pastel, chất cotton mềm, phong cách trẻ trung.'],
            ['name' => 'Áo len nữ cổ lọ kem', 'category' => 'ao_nu', 'price' => 420000, 'sale_price' => 350000, 'is_featured' => true, 'description' => 'Áo len nữ cổ lọ màu kem, chất liệu len mịn, ấm áp và thời trang.'],

            // Quần nam
            ['name' => 'Quần jean nam slim fit xanh đậm', 'category' => 'quan_nam', 'price' => 450000, 'sale_price' => 380000, 'is_featured' => true, 'description' => 'Quần jean nam slim fit màu xanh đậm, chất denim co giãn nhẹ, thoải mái khi vận động.'],
            ['name' => 'Quần tây nam đen công sở', 'category' => 'quan_nam', 'price' => 520000, 'sale_price' => null, 'is_featured' => false, 'description' => 'Quần tây nam màu đen, chất liệu polyester cao cấp, form regular, phù hợp đi làm.'],
            ['name' => 'Quần short nam kaki be', 'category' => 'quan_nam', 'price' => 280000, 'sale_price' => 230000, 'is_featured' => false, 'description' => 'Quần short nam kaki màu be, chất liệu cotton thoáng mát, phù hợp mùa hè.'],

            // Quần nữ
            ['name' => 'Quần jean nữ ống rộng trắng', 'category' => 'quan_nu', 'price' => 420000, 'sale_price' => 350000, 'is_featured' => true, 'description' => 'Quần jean nữ ống rộng màu trắng, phong cách retro hiện đại, tôn dáng.'],
            ['name' => 'Quần culottes nữ đen', 'category' => 'quan_nu', 'price' => 320000, 'sale_price' => null, 'is_featured' => false, 'description' => 'Quần culottes nữ màu đen, chất liệu voan nhẹ, thanh lịch và thoải mái.'],

            // Váy & Đầm
            ['name' => 'Đầm maxi hoa nhí xanh lá', 'category' => 'vay_dam', 'price' => 580000, 'sale_price' => 480000, 'is_featured' => true, 'description' => 'Đầm maxi họa tiết hoa nhí màu xanh lá, chất liệu voan mềm, phù hợp đi biển và dạo phố.'],
            ['name' => 'Váy midi chữ A đỏ đô', 'category' => 'vay_dam', 'price' => 450000, 'sale_price' => null, 'is_featured' => true, 'description' => 'Váy midi chữ A màu đỏ đô, chất liệu lụa satin bóng mịn, sang trọng và quyến rũ.'],
            ['name' => 'Đầm body đen dự tiệc', 'category' => 'vay_dam', 'price' => 680000, 'sale_price' => 580000, 'is_featured' => false, 'description' => 'Đầm body màu đen ôm dáng, chất liệu thun co giãn 4 chiều, phù hợp dự tiệc.'],

            // Phụ kiện
            ['name' => 'Thắt lưng da nam nâu', 'category' => 'phu_kien', 'price' => 250000, 'sale_price' => 199000, 'is_featured' => false, 'description' => 'Thắt lưng da bò thật màu nâu, khóa kim loại mạ vàng, bền đẹp theo thời gian.'],
            ['name' => 'Túi xách nữ mini đen', 'category' => 'phu_kien', 'price' => 380000, 'sale_price' => null, 'is_featured' => true, 'description' => 'Túi xách nữ mini màu đen, chất liệu da PU cao cấp, thiết kế đơn giản mà tinh tế.'],
            ['name' => 'Mũ bucket unisex be', 'category' => 'phu_kien', 'price' => 150000, 'sale_price' => 120000, 'is_featured' => false, 'description' => 'Mũ bucket unisex màu be, chất liệu cotton dày dặn, phong cách streetwear.'],
        ];

        foreach ($products as $index => $product) {
            Food::create([
                'name'        => $product['name'],
                'slug'        => Str::slug($product['name']) . '-' . ($index + 1),
                'description' => $product['description'],
                'price'       => $product['price'],
                'sale_price'  => $product['sale_price'],
                'image'       => null,
                'category'    => $product['category'],
                'stock'       => rand(10, 100),
                'is_featured' => $product['is_featured'],
                'status'      => true,
            ]);
        }
    }
}
