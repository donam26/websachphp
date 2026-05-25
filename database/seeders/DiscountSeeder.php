<?php

namespace Database\Seeders;

use App\Models\Discount;
use Illuminate\Database\Seeder;

class DiscountSeeder extends Seeder
{
    public function run()
    {
        $now = now();

        $discounts = [
            [
                'code' => 'WELCOME10',
                'name' => 'Chào mừng khách mới - giảm 10%',
                'description' => 'Áp dụng cho đơn hàng đầu tiên, giảm tối đa 50.000đ',
                'type' => 'percent',
                'value' => 10,
                'min_order_amount' => 100000,
                'max_discount_amount' => 50000,
                'usage_limit' => 1000,
                'used_count' => 0,
                'start_date' => $now->copy()->subDay(),
                'end_date' => $now->copy()->addMonths(3),
                'is_active' => true,
            ],
            [
                'code' => 'BOOK50K',
                'name' => 'Giảm 50.000đ cho đơn từ 300K',
                'description' => 'Áp dụng cho mọi đơn hàng từ 300.000đ',
                'type' => 'fixed',
                'value' => 50000,
                'min_order_amount' => 300000,
                'max_discount_amount' => null,
                'usage_limit' => 500,
                'used_count' => 0,
                'start_date' => $now->copy()->subDay(),
                'end_date' => $now->copy()->addMonths(2),
                'is_active' => true,
            ],
            [
                'code' => 'FREESHIP',
                'name' => 'Miễn phí vận chuyển',
                'description' => 'Giảm 30.000đ phí vận chuyển',
                'type' => 'fixed',
                'value' => 30000,
                'min_order_amount' => 0,
                'max_discount_amount' => null,
                'usage_limit' => null,
                'used_count' => 0,
                'start_date' => $now->copy()->subDay(),
                'end_date' => $now->copy()->addYear(),
                'is_active' => true,
            ],
            [
                'code' => 'EXPIRED20',
                'name' => 'Mã hết hạn (demo)',
                'description' => 'Mã giảm giá đã hết hạn dùng để demo',
                'type' => 'percent',
                'value' => 20,
                'min_order_amount' => 0,
                'max_discount_amount' => null,
                'usage_limit' => null,
                'used_count' => 0,
                'start_date' => $now->copy()->subMonths(3),
                'end_date' => $now->copy()->subMonth(),
                'is_active' => true,
            ],
        ];

        foreach ($discounts as $row) {
            Discount::updateOrCreate(['code' => $row['code']], $row);
        }
    }
}
