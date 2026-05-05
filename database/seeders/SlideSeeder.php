<?php

namespace Database\Seeders;

use App\Models\Slide;
use Illuminate\Database\Seeder;

class SlideSeeder extends Seeder
{
    public function run(): void
    {
        $slides = [
            [
                'title'       => 'Bộ sưu tập mới 2026',
                'subtitle'    => 'NEW COLLECTION',
                'description' => 'Thời trang hiện đại, chất liệu cao cấp, thiết kế tinh tế dành cho mọi phong cách sống.',
                'image'       => 'https://picsum.photos/id/1062/1600/800',
                'link'        => '/foods',
                'button_text' => 'Khám phá ngay',
                'order'       => 1,
                'is_active'   => true,
            ],
            [
                'title'       => 'Thời trang nữ thanh lịch',
                'subtitle'    => 'WOMEN FASHION',
                'description' => 'Váy đầm, áo nữ đa dạng kiểu dáng, phù hợp mọi dịp từ công sở đến dạo phố.',
                'image'       => 'https://picsum.photos/id/1027/1600/800',
                'link'        => '/category/ao_nu',
                'button_text' => 'Xem ngay',
                'order'       => 2,
                'is_active'   => true,
            ],
            [
                'title'       => 'Thời trang nam lịch lãm',
                'subtitle'    => 'MEN FASHION',
                'description' => 'Áo sơ mi, áo thun, quần tây, quần jean đủ phong cách cho người đàn ông hiện đại.',
                'image'       => 'https://picsum.photos/id/1005/1600/800',
                'link'        => '/category/ao_nam',
                'button_text' => 'Xem ngay',
                'order'       => 3,
                'is_active'   => true,
            ],
            [
                'title'       => 'Sale mùa hè giảm đến 50%',
                'subtitle'    => 'SUMMER SALE',
                'description' => 'Hàng trăm mẫu áo, quần, váy đầm đang được giảm giá mạnh. Nhanh tay sở hữu ngay!',
                'image'       => 'https://picsum.photos/id/1035/1600/800',
                'link'        => '/foods',
                'button_text' => 'Mua ngay',
                'order'       => 4,
                'is_active'   => true,
            ],
        ];

        foreach ($slides as $slide) {
            Slide::create($slide);
        }
    }
}
