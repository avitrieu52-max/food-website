<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Food;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

/**
 * Seeder tạo dữ liệu mẫu cho các sản phẩm thời trang.
 * Sử dụng hình ảnh từ thư mục public/images/foods
 */
class FoodSeeder extends Seeder
{
    public function run(): void
    {
        // Lấy danh sách category
        $categories = Category::all()->pluck('id', 'slug');

        // Danh sách sản phẩm mẫu với hình ảnh từ Unsplash (fashion)
        $products = [
            // Áo nam
            [
                'name' => 'Áo thun nam cotton cao cấp',
                'description' => 'Áo thun nam chất liệu cotton thoáng mát, form slimfit ôm body. Thiết kế đơn giản, dễ phối đồ.',
                'price' => 250000,
                'sale_price' => 199000,
                'image' => 'https://images.unsplash.com/photo-1521572163474-6864f9cf17ab?w=400&h=400&fit=crop',
                'category_slug' => 'ao_nam',
                'stock' => 50,
                'is_featured' => true,
            ],
            [
                'name' => 'Áo sơ mi nam công sở',
                'description' => 'Áo sơ mi nam tay dài, chất liệu cotton pha, form regular. Phù hợp mặc công sở, đi chơi.',
                'price' => 350000,
                'sale_price' => null,
                'image' => 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=400&h=400&fit=crop',
                'category_slug' => 'ao_nam',
                'stock' => 30,
                'is_featured' => false,
            ],
            [
                'name' => 'Áo khoác nam bomber',
                'description' => 'Áo khoác nam kiểu bomber, chất liệu dù chống nước. Thiết kế trẻ trung, năng động.',
                'price' => 450000,
                'sale_price' => 399000,
                'image' => 'https://images.unsplash.com/photo-1551028719-00167b16eac5?w=400&h=400&fit=crop',
                'category_slug' => 'ao_nam',
                'stock' => 20,
                'is_featured' => true,
            ],
            [
                'name' => 'Áo len nam cổ lọ',
                'description' => 'Áo len nam cổ lọ, chất liệu len acrylic ấm áp. Phù hợp mặc mùa đông.',
                'price' => 320000,
                'sale_price' => null,
                'image' => 'https://images.unsplash.com/photo-1576566588028-4147f3842f27?w=400&h=400&fit=crop',
                'category_slug' => 'ao_nam',
                'stock' => 25,
                'is_featured' => false,
            ],
            [
                'name' => 'Áo polo nam thể thao',
                'description' => 'Áo polo nam chất liệu thun lạnh, co giãn tốt. Thiết kế thể thao, năng động.',
                'price' => 280000,
                'sale_price' => 249000,
                'image' => 'https://images.unsplash.com/photo-1586790170083-2f9ceadc732d?w=400&h=400&fit=crop',
                'category_slug' => 'ao_nam',
                'stock' => 40,
                'is_featured' => false,
            ],

            // Áo nữ
            [
                'name' => 'Áo thun nữ crop top',
                'description' => 'Áo thun nữ kiểu crop top, chất liệu cotton mỏng nhẹ. Thiết kế trẻ trung, sexy.',
                'price' => 220000,
                'sale_price' => 189000,
                'image' => 'https://images.unsplash.com/photo-1434389677669-e08b4cac3105?w=400&h=400&fit=crop',
                'category_slug' => 'ao_nu',
                'stock' => 35,
                'is_featured' => true,
            ],
            [
                'name' => 'Áo sơ mi nữ công sở',
                'description' => 'Áo sơ mi nữ tay dài, chất liệu voan thoáng mát. Form regular, dễ chịu.',
                'price' => 320000,
                'sale_price' => null,
                'image' => 'https://images.unsplash.com/photo-1551698618-1dfe5d97d256?w=400&h=400&fit=crop',
                'category_slug' => 'ao_nu',
                'stock' => 28,
                'is_featured' => false,
            ],
            [
                'name' => 'Áo khoác nữ cardigan',
                'description' => 'Áo khoác nữ kiểu cardigan len, chất liệu len mềm mại. Phù hợp layering.',
                'price' => 380000,
                'sale_price' => 329000,
                'image' => 'https://images.unsplash.com/photo-1591047139829-d91aecb6caea?w=400&h=400&fit=crop',
                'category_slug' => 'ao_nu',
                'stock' => 22,
                'is_featured' => true,
            ],
            [
                'name' => 'Áo len nữ cổ tròn',
                'description' => 'Áo len nữ cổ tròn basic, chất liệu cotton pha len. Thiết kế đơn giản, dễ mix.',
                'price' => 290000,
                'sale_price' => null,
                'image' => 'https://images.unsplash.com/photo-1576566588028-4147f3842f27?w=400&h=400&fit=crop',
                'category_slug' => 'ao_nu',
                'stock' => 30,
                'is_featured' => false,
            ],
            [
                'name' => 'Áo blouse nữ tay phồng',
                'description' => 'Áo blouse nữ tay phồng, chất liệu chiffon bay. Thiết kế nữ tính, thanh lịch.',
                'price' => 350000,
                'sale_price' => 299000,
                'image' => 'https://images.unsplash.com/photo-1581044777550-4cfa60707c03?w=400&h=400&fit=crop',
                'category_slug' => 'ao_nu',
                'stock' => 18,
                'is_featured' => false,
            ],

            // Quần nam
            [
                'name' => 'Quần jean nam slimfit',
                'description' => 'Quần jean nam chất liệu denim co giãn. Form slimfit ôm body, trẻ trung.',
                'price' => 450000,
                'sale_price' => 399000,
                'image' => 'https://images.unsplash.com/photo-1542272604-787c3835535d?w=400&h=400&fit=crop',
                'category_slug' => 'quan_nam',
                'stock' => 45,
                'is_featured' => true,
            ],
            [
                'name' => 'Quần kaki nam công sở',
                'description' => 'Quần kaki nam chất liệu cotton pha. Form regular, phù hợp công sở.',
                'price' => 380000,
                'sale_price' => null,
                'image' => 'https://images.unsplash.com/photo-1473966968600-fa801b869a1a?w=400&h=400&fit=crop',
                'category_slug' => 'quan_nam',
                'stock' => 32,
                'is_featured' => false,
            ],
            [
                'name' => 'Quần short nam thể thao',
                'description' => 'Quần short nam chất liệu thun lạnh. Thiết kế thể thao, thoáng mát.',
                'price' => 250000,
                'sale_price' => 199000,
                'image' => 'https://images.unsplash.com/photo-1506629905607-0b5ab9a9e21a?w=400&h=400&fit=crop',
                'category_slug' => 'quan_nam',
                'stock' => 40,
                'is_featured' => false,
            ],
            [
                'name' => 'Quần jogger nam',
                'description' => 'Quần jogger nam chất liệu nỉ. Form ống loe, phong cách streetwear.',
                'price' => 320000,
                'sale_price' => null,
                'image' => 'https://images.unsplash.com/photo-1506629905607-0b5ab9a9e21a?w=400&h=400&fit=crop',
                'category_slug' => 'quan_nam',
                'stock' => 25,
                'is_featured' => true,
            ],
            [
                'name' => 'Quần tây nam công sở',
                'description' => 'Quần tây nam chất liệu wool pha. Form slim, lịch sự.',
                'price' => 420000,
                'sale_price' => 379000,
                'image' => 'https://images.unsplash.com/photo-1506629905607-0b5ab9a9e21a?w=400&h=400&fit=crop',
                'category_slug' => 'quan_nam',
                'stock' => 20,
                'is_featured' => false,
            ],

            // Quần nữ
            [
                'name' => 'Quần jean nữ skinny',
                'description' => 'Quần jean nữ form skinny ôm sát. Chất liệu denim co giãn thoải mái.',
                'price' => 420000,
                'sale_price' => 369000,
                'image' => 'https://images.unsplash.com/photo-1584464491033-06628f3a6b7b?w=400&h=400&fit=crop',
                'category_slug' => 'quan_nu',
                'stock' => 38,
                'is_featured' => true,
            ],
            [
                'name' => 'Quần legging nữ thể thao',
                'description' => 'Quần legging nữ chất liệu spandex. Co giãn tốt, phù hợp gym, yoga.',
                'price' => 280000,
                'sale_price' => null,
                'image' => 'https://images.unsplash.com/photo-1506629905607-0b5ab9a9e21a?w=400&h=400&fit=crop',
                'category_slug' => 'quan_nu',
                'stock' => 50,
                'is_featured' => false,
            ],
            [
                'name' => 'Quần short nữ denim',
                'description' => 'Quần short nữ chất liệu denim mỏng. Thiết kế trẻ trung, năng động.',
                'price' => 320000,
                'sale_price' => 279000,
                'image' => 'https://images.unsplash.com/photo-1584464491033-06628f3a6b7b?w=400&h=400&fit=crop',
                'category_slug' => 'quan_nu',
                'stock' => 30,
                'is_featured' => false,
            ],
            [
                'name' => 'Quần culottes nữ',
                'description' => 'Quần culottes nữ chất liệu cotton. Form rộng, phong cách Hàn Quốc.',
                'price' => 350000,
                'sale_price' => null,
                'image' => 'https://images.unsplash.com/photo-1584464491033-06628f3a6b7b?w=400&h=400&fit=crop',
                'category_slug' => 'quan_nu',
                'stock' => 22,
                'is_featured' => true,
            ],
            [
                'name' => 'Quần ống rộng nữ',
                'description' => 'Quần ống rộng nữ chất liệu linen thoáng mát. Thiết kế bohemian.',
                'price' => 380000,
                'sale_price' => 329000,
                'image' => 'https://images.unsplash.com/photo-1584464491033-06628f3a6b7b?w=400&h=400&fit=crop',
                'category_slug' => 'quan_nu',
                'stock' => 18,
                'is_featured' => false,
            ],

            // Váy & Đầm
            [
                'name' => 'Đầm bodycon nữ',
                'description' => 'Đầm bodycon ôm sát, chất liệu spandex co giãn. Thiết kế sexy, quyến rũ.',
                'price' => 450000,
                'sale_price' => 399000,
                'image' => 'https://images.unsplash.com/photo-1595777457583-95e059d581b8?w=400&h=400&fit=crop',
                'category_slug' => 'vay_dam',
                'stock' => 25,
                'is_featured' => true,
            ],
            [
                'name' => 'Đầm maxi hoa',
                'description' => 'Đầm maxi in hoa, chất liệu chiffon bay. Phong cách bohemian, nữ tính.',
                'price' => 520000,
                'sale_price' => null,
                'image' => 'https://images.unsplash.com/photo-1572804013309-59a88b7e92f1?w=400&h=400&fit=crop',
                'category_slug' => 'vay_dam',
                'stock' => 15,
                'is_featured' => false,
            ],
            [
                'name' => 'Đầm công sở nữ',
                'description' => 'Đầm công sở tay lỡ, chất liệu cotton pha. Thiết kế thanh lịch, chuyên nghiệp.',
                'price' => 480000,
                'sale_price' => 429000,
                'image' => 'https://images.unsplash.com/photo-1551698618-1dfe5d97d256?w=400&h=400&fit=crop',
                'category_slug' => 'vay_dam',
                'stock' => 20,
                'is_featured' => true,
            ],
            [
                'name' => 'Đầm suông nữ',
                'description' => 'Đầm suông chất liệu linen thoáng mát. Thiết kế đơn giản, dễ phối đồ.',
                'price' => 420000,
                'sale_price' => null,
                'image' => 'https://images.unsplash.com/photo-1572804013309-59a88b7e92f1?w=400&h=400&fit=crop',
                'category_slug' => 'vay_dam',
                'stock' => 28,
                'is_featured' => false,
            ],
            [
                'name' => 'Đầm dạ tiệc',
                'description' => 'Đầm dạ tiệc chất liệu lụa, thiết kế ôm eo. Sang trọng, quyến rũ.',
                'price' => 650000,
                'sale_price' => 579000,
                'image' => 'https://images.unsplash.com/photo-1595777457583-95e059d581b8?w=400&h=400&fit=crop',
                'category_slug' => 'vay_dam',
                'stock' => 12,
                'is_featured' => false,
            ],

            // Phụ kiện
            [
                'name' => 'Túi xách nữ da PU',
                'description' => 'Túi xách nữ chất liệu da PU cao cấp. Thiết kế tote, tiện dụng.',
                'price' => 380000,
                'sale_price' => 329000,
                'image' => 'https://images.unsplash.com/photo-1553062407-98eeb64c6a62?w=400&h=400&fit=crop',
                'category_slug' => 'phu_kien',
                'stock' => 35,
                'is_featured' => true,
            ],
            [
                'name' => 'Nón bucket nữ',
                'description' => 'Nón bucket chất liệu vải canvas. Thiết kế trendy, chống nắng tốt.',
                'price' => 180000,
                'sale_price' => null,
                'image' => 'https://images.unsplash.com/photo-1578662996442-48f60103fc96?w=400&h=400&fit=crop',
                'category_slug' => 'phu_kien',
                'stock' => 40,
                'is_featured' => false,
            ],
            [
                'name' => 'Dây chuyền vàng 18k',
                'description' => 'Dây chuyền vàng 18k mạ vàng. Thiết kế đơn giản, tinh tế.',
                'price' => 250000,
                'sale_price' => 199000,
                'image' => 'https://images.unsplash.com/photo-1515372039744-b8f02a3ae446?w=400&h=400&fit=crop',
                'category_slug' => 'phu_kien',
                'stock' => 50,
                'is_featured' => true,
            ],
            [
                'name' => 'Kính mát unisex',
                'description' => 'Kính mát chất liệu nhựa cao cấp. Thiết kế retro, chống UV 100%.',
                'price' => 220000,
                'sale_price' => null,
                'image' => 'https://images.unsplash.com/photo-1572635196237-14b3f281503f?w=400&h=400&fit=crop',
                'category_slug' => 'phu_kien',
                'stock' => 30,
                'is_featured' => false,
            ],
            [
                'name' => 'Ví da nam',
                'description' => 'Ví da nam chất liệu da bò thật. Thiết kế slim, tiện lợi.',
                'price' => 320000,
                'sale_price' => 279000,
                'image' => 'https://images.unsplash.com/photo-1627123424574-724758594e93?w=400&h=400&fit=crop',
                'category_slug' => 'phu_kien',
                'stock' => 25,
                'is_featured' => false,
            ],
        ];

        // Tạo sản phẩm
        foreach ($products as $product) {
            Food::updateOrCreate([
                'slug' => Str::slug($product['name']),
            ], [
                'name'        => $product['name'],
                'description' => $product['description'],
                'price'       => $product['price'],
                'sale_price'  => $product['sale_price'],
                'image'       => $product['image'],
                'category_id' => $categories[$product['category_slug']],
                'stock'       => $product['stock'],
                'is_featured' => $product['is_featured'],
                'status'      => true,
            ]);
        }
    }
}
