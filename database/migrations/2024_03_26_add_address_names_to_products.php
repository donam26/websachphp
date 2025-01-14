<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->string('province_name')->nullable()->after('province_id');
            $table->string('district_name')->nullable()->after('district_id');
            $table->string('ward_name')->nullable()->after('ward_id');
            $table->string('street')->nullable()->after('ward_name');
        });

        // Copy data từ street_id sang street
        if (Schema::hasColumn('products', 'street_id')) {
            DB::statement('UPDATE products SET street = street_id');
            
            Schema::table('products', function (Blueprint $table) {
                $table->dropColumn('street_id');
            });
        }
    }

    public function down()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->string('street_id')->nullable()->after('ward_id');
        });

        // Copy data từ street sang street_id
        DB::statement('UPDATE products SET street_id = street');

        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['province_name', 'district_name', 'ward_name', 'street']);
        });
    }
}; 