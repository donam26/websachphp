<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Customers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->id(); // Tạo cột 'id'
            $table->text('name')->nullable();
            $table->text('ward_id')->nullable();
            $table->text('user_id')->nullable();
            $table->text('district_id')->nullable();
            $table->text('province_id')->nullable();
            $table->text('street_id')->nullable();
            $table->text('house_number')->nullable();
            $table->text('formality')->nullable(); // Hình thức
            $table->text('type')->nullable(); // Loại BĐS
            $table->text('currency')->nullable();
            $table->decimal('price', 28, 0)->nullable();
            $table->decimal('width', 10, 2)->nullable();
            $table->decimal('length', 10, 2)->nullable();
            $table->decimal('acreage', 10, 2)->nullable();
            $table->boolean('is_hot')->default(false);
            $table->text('host_name')->nullable();
            $table->text('host_phone1')->nullable();
            $table->text('host_phone2')->nullable();
            $table->text('stressid')->nullable();
            $table->text('ward_name')->nullable();
            $table->text('district_name')->nullable();
            $table->text('province_name')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('customers'); // Xóa bảng nếu cần rollback
    }
}
