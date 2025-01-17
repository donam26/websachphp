<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique(); // Mã sản phẩm unique
            $table->string('title')->nullable();
            $table->text('content')->nullable();
            $table->string('ward_id')->nullable();
            $table->string('user_id')->nullable();
            $table->string('district_id')->nullable();
            $table->string('province_id')->nullable();
            $table->string('street_id')->nullable();
            $table->string('house_number')->nullable();
            $table->string('formality')->nullable(); // Hình thức
            $table->string('type')->nullable(); // Loại BĐS
            $table->string('expand_style')->nullable();
            $table->string('currency')->nullable();
            $table->string('type_of_payment')->nullable();
            $table->text('expand_style_info')->nullable();
            $table->decimal('price', 15, 2)->nullable();
            $table->decimal('transfer_price', 15, 2)->nullable();
            $table->decimal('input_price', 15, 2)->nullable();
            $table->decimal('width', 10, 2)->nullable();
            $table->decimal('length', 10, 2)->nullable();
            $table->decimal('acreage', 10, 2)->nullable();
            $table->integer('floor_number')->nullable();
            $table->integer('corner_number')->nullable();
            $table->string('badger')->nullable();
            $table->boolean('show_in_web')->default(true);
            $table->boolean('has_corner')->default(false);
            $table->boolean('elevator')->default(false);
            $table->boolean('terrace')->default(false);
            $table->boolean('basement')->default(false);
            $table->integer('basement_number')->nullable();
            $table->integer('elevator_number')->nullable();
            $table->string('direction')->nullable();
            $table->string('class')->nullable();
            $table->decimal('floor_area', 10, 2)->nullable();
            $table->text('rental_area_description')->nullable();
            $table->text('area_description')->nullable();
            $table->decimal('rental_area', 10, 2)->nullable();
            $table->decimal('service_fee', 15, 2)->nullable();
            $table->decimal('car_parking_fee', 15, 2)->nullable();
            $table->decimal('moto_parking_fee', 15, 2)->nullable();
            $table->decimal('overtime_fee', 15, 2)->nullable();
            $table->string('overtime_fee_type')->nullable();
            $table->decimal('electricity_fee', 15, 2)->nullable();
            $table->string('deposit_time')->nullable();
            $table->string('pay_time')->nullable();
            $table->string('lease_term')->nullable();
            $table->string('decor_time')->nullable();
            $table->string('electricity_fee_type')->nullable();
            $table->text('price_description')->nullable();
            $table->string('product_type')->nullable();
            $table->string('name')->nullable();
            $table->string('name_normalize')->nullable();
            $table->boolean('is_rent_all_apartment')->default(false);
            $table->boolean('is_hot')->default(false);
            $table->boolean('is_hidden_phone')->default(false);
            $table->decimal('commission', 10, 2)->nullable();
            $table->enum('status', ['active', 'inactive', 'pending', 'sold', 'rented'])->default('active');
            $table->string('host_name')->nullable();
            $table->string('host_phone1')->nullable();
            $table->string('host_phone2')->nullable();
            $table->string('transfer_price_currency')->nullable();
            $table->string('input_tl')->nullable();
            $table->decimal('total_price', 15, 2)->nullable();
            $table->string('youtube')->nullable();
            $table->date('expire_contract_date')->nullable();
            $table->string('close_deal_type')->nullable();
            $table->decimal('area_by_book', 10, 2)->nullable();
            $table->string('source_id')->nullable();
            $table->string('contact_id')->nullable();
            $table->integer('room_number_total')->nullable();
            $table->decimal('rating_stars', 3, 1)->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('products');
    }
}; 