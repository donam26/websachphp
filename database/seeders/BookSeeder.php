<?php

namespace Database\Seeders;

use App\Models\Book;
use App\Models\Category;
use Illuminate\Database\Seeder;

class BookSeeder extends Seeder
{
    public function run()
    {
        // Tạo danh mục thời trang
        $categories = [
            ['name' => 'Áo thun', 'icon' => 'bi bi-circle', 'description' => 'Áo thun nam nữ các loại'],
            ['name' => 'Áo sơ mi', 'icon' => 'bi bi-circle', 'description' => 'Áo sơ mi công sở, casual'],
            ['name' => 'Áo khoác', 'icon' => 'bi bi-circle', 'description' => 'Áo khoác, jacket, hoodie'],
            ['name' => 'Quần jeans', 'icon' => 'bi bi-circle', 'description' => 'Quần jeans nam nữ'],
            ['name' => 'Quần kaki', 'icon' => 'bi bi-circle', 'description' => 'Quần kaki, quần tây'],
            ['name' => 'Quần short', 'icon' => 'bi bi-circle', 'description' => 'Quần short, quần đùi'],
            ['name' => 'Váy - Đầm', 'icon' => 'bi bi-circle', 'description' => 'Váy, đầm nữ các loại'],
            ['name' => 'Phụ kiện', 'icon' => 'bi bi-circle', 'description' => 'Mũ, thắt lưng, túi xách'],
        ];

        foreach ($categories as $cat) {
            Category::firstOrCreate(['name' => $cat['name']], $cat);
        }

        // Tạo sản phẩm mẫu
        $products = [
            [
                'title' => 'Áo Thun Basic Cotton',
                'brand' => 'Uniqlo',
                'author' => 'Uniqlo',
                'description' => 'Áo thun basic cotton 100% thoáng mát, phù hợp mặc hàng ngày. Chất liệu cotton mềm mại, thấm hút mồ hôi tốt.',
                'price' => 199000,
                'quantity' => 100,
                'sizes' => 'S,M,L,XL,XXL',
                'colors' => 'Đen,Trắng,Xám,Navy',
                'material' => 'Cotton 100%',
                'gender' => 'unisex',
                'status' => 'available',
            ],
            [
                'title' => 'Áo Sơ Mi Oxford Slim Fit',
                'brand' => 'Zara',
                'author' => 'Zara',
                'description' => 'Áo sơ mi Oxford form slim fit thanh lịch, phù hợp đi làm và dự tiệc. Chất liệu vải Oxford cao cấp.',
                'price' => 450000,
                'quantity' => 50,
                'sizes' => 'S,M,L,XL',
                'colors' => 'Trắng,Xanh nhạt,Hồng nhạt',
                'material' => 'Oxford Cotton',
                'gender' => 'nam',
                'status' => 'available',
            ],
            [
                'title' => 'Quần Jeans Skinny Fit',
                'brand' => 'Levi\'s',
                'author' => 'Levi\'s',
                'description' => 'Quần jeans skinny fit co giãn thoải mái, phù hợp nhiều phong cách. Wash đậm trẻ trung.',
                'price' => 650000,
                'quantity' => 40,
                'sizes' => '28,29,30,31,32,34',
                'colors' => 'Xanh đậm,Xanh nhạt,Đen',
                'material' => 'Denim co giãn',
                'gender' => 'unisex',
                'status' => 'available',
            ],
            [
                'title' => 'Áo Khoác Hoodie Oversize',
                'brand' => 'Nike',
                'author' => 'Nike',
                'description' => 'Hoodie oversize phong cách streetwear, chất nỉ bông dày dặn, giữ ấm tốt.',
                'price' => 890000,
                'quantity' => 30,
                'sizes' => 'M,L,XL,XXL',
                'colors' => 'Đen,Xám,Be',
                'material' => 'Nỉ bông French Terry',
                'gender' => 'unisex',
                'status' => 'available',
            ],
            [
                'title' => 'Đầm Midi Hoa Nhí',
                'brand' => 'H&M',
                'author' => 'H&M',
                'description' => 'Đầm midi họa tiết hoa nhí vintage, chất liệu voan nhẹ nhàng, nữ tính.',
                'price' => 520000,
                'quantity' => 25,
                'sizes' => 'S,M,L',
                'colors' => 'Trắng hoa,Xanh hoa,Hồng hoa',
                'material' => 'Voan cao cấp',
                'gender' => 'nu',
                'status' => 'available',
            ],
            [
                'title' => 'Quần Short Kaki',
                'brand' => 'GAP',
                'author' => 'GAP',
                'description' => 'Quần short kaki thoáng mát cho mùa hè, thiết kế basic dễ phối đồ.',
                'price' => 350000,
                'quantity' => 60,
                'sizes' => 'S,M,L,XL',
                'colors' => 'Be,Xanh rêu,Nâu',
                'material' => 'Kaki cotton',
                'gender' => 'nam',
                'status' => 'available',
            ],
        ];

        $categoryIds = Category::pluck('id', 'name');

        foreach ($products as $product) {
            $catName = match(true) {
                str_contains($product['title'], 'Áo Thun') => 'Áo thun',
                str_contains($product['title'], 'Sơ Mi') => 'Áo sơ mi',
                str_contains($product['title'], 'Khoác') || str_contains($product['title'], 'Hoodie') => 'Áo khoác',
                str_contains($product['title'], 'Jeans') => 'Quần jeans',
                str_contains($product['title'], 'Short') => 'Quần short',
                str_contains($product['title'], 'Đầm') || str_contains($product['title'], 'Váy') => 'Váy - Đầm',
                default => 'Áo thun',
            };

            $product['category_id'] = $categoryIds[$catName] ?? null;
            Book::firstOrCreate(['title' => $product['title']], $product);
        }
    }
}
