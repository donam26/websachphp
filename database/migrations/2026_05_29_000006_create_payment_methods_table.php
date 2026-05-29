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
            $table->string('code')->unique();   // machine key, matches orders.payment_method
            $table->string('name');             // method_name (hiển thị)
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Lookup/reference rows mirroring the existing enum values.
        DB::table('payment_methods')->insert([
            ['code' => 'cod', 'name' => 'Thanh toán khi nhận hàng (COD)', 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['code' => 'vnpay', 'name' => 'VNPAY', 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('payment_methods');
    }
};
