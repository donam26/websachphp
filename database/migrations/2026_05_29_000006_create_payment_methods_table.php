<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payment_methods', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();        // machine key, matches orders.payment_method
            $table->string('name');                  // tên hiển thị
            $table->string('description')->nullable(); // mô tả ngắn hiển thị ở trang thanh toán
            $table->string('icon')->nullable();      // class icon Bootstrap (vd: bi-cash-coin)
            $table->boolean('is_active')->default(true);
            $table->unsignedSmallInteger('sort_order')->default(0); // thứ tự hiển thị
            $table->timestamps();
        });

        // Lookup/reference rows mirroring the existing enum values.
        DB::table('payment_methods')->insert([
            [
                'code' => 'cod',
                'name' => 'Thanh toán khi nhận hàng (COD)',
                'description' => 'Trả tiền mặt khi shipper giao',
                'icon' => 'bi-cash-coin text-warning',
                'is_active' => true,
                'sort_order' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => 'vnpay',
                'name' => 'VNPAY',
                'description' => 'Thẻ ATM/Visa/Master/QR qua cổng VNPAY',
                'icon' => 'bi-credit-card-2-front text-info',
                'is_active' => true,
                'sort_order' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('payment_methods');
    }
};
