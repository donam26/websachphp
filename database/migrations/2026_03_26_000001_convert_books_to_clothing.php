<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Thêm fields quần áo vào bảng books
        Schema::table('books', function (Blueprint $table) {
            $table->string('brand')->nullable()->after('author');
            $table->string('sizes')->nullable()->after('description'); // VD: S,M,L,XL,XXL
            $table->string('colors')->nullable()->after('sizes'); // VD: Đen,Trắng,Đỏ
            $table->string('material')->nullable()->after('colors'); // Chất liệu: Cotton, Polyester...
            $table->enum('gender', ['nam', 'nu', 'unisex'])->default('unisex')->after('material');
        });

        // Thêm size và color vào cart_items
        Schema::table('cart_items', function (Blueprint $table) {
            $table->string('size')->nullable()->after('quantity');
            $table->string('color')->nullable()->after('size');
        });

        // Thêm size và color vào order_items
        Schema::table('order_items', function (Blueprint $table) {
            $table->string('size')->nullable()->after('price');
            $table->string('color')->nullable()->after('size');
        });
    }

    public function down()
    {
        Schema::table('books', function (Blueprint $table) {
            $table->dropColumn(['brand', 'sizes', 'colors', 'material', 'gender']);
        });

        Schema::table('cart_items', function (Blueprint $table) {
            $table->dropColumn(['size', 'color']);
        });

        Schema::table('order_items', function (Blueprint $table) {
            $table->dropColumn(['size', 'color']);
        });
    }
};
