<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Adds the ERD FK Order n—1 Payment_Method. The legacy orders.payment_method
 * enum is kept as the behavioural discriminator (VNPay/COD flow); this FK is
 * additive and backfilled from that enum by matching payment_methods.code.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->foreignId('payment_method_id')->nullable()->after('payment_method')
                  ->constrained('payment_methods')->nullOnDelete();
        });

        foreach (DB::table('payment_methods')->pluck('id', 'code') as $code => $id) {
            DB::table('orders')->where('payment_method', $code)->update(['payment_method_id' => $id]);
        }
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropConstrainedForeignId('payment_method_id');
        });
    }
};
