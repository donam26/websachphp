<?php

namespace Database\Seeders;

use App\Models\Supplier;
use Illuminate\Database\Seeder;

class SupplierSeeder extends Seeder
{
    public function run(): void
    {
        $suppliers = [
            ['name' => 'Công ty CP Sách Alpha (Alpha Books)', 'phone' => '02473086886', 'email' => 'contact@alphabooks.vn', 'address' => '176 Thái Hà, Đống Đa, Hà Nội'],
            ['name' => 'Nhà xuất bản Trẻ', 'phone' => '02839316289', 'email' => 'hopthubandoc@nxbtre.com.vn', 'address' => '161B Lý Chính Thắng, Quận 3, TP.HCM'],
            ['name' => 'Nhà xuất bản Kim Đồng', 'phone' => '02439434730', 'email' => 'info@nxbkimdong.com.vn', 'address' => '55 Quang Trung, Hai Bà Trưng, Hà Nội'],
            ['name' => 'Công ty Văn hóa Sáng tạo Trí Việt (First News)', 'phone' => '02838227979', 'email' => 'triviet@firstnews.com.vn', 'address' => '11H Nguyễn Thị Minh Khai, Quận 1, TP.HCM'],
            ['name' => 'Nhà sách Fahasa', 'phone' => '02838225796', 'email' => 'cskh@fahasa.com.vn', 'address' => '60-62 Lê Lợi, Quận 1, TP.HCM'],
        ];

        foreach ($suppliers as $supplier) {
            Supplier::create($supplier + ['status' => 'active']);
        }
    }
}
