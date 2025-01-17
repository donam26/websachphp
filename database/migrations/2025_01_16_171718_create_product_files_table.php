<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductFilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_files', function (Blueprint $table) {
            $table->id();
            $table->string('product_code');
            $table->string('name');
            $table->string('path');
            $table->string('type')->nullable();
            $table->bigInteger('size')->nullable();
            $table->text('description')->nullable();
            $table->timestamps();

            $table->foreign('product_code')
                  ->references('code')
                  ->on('products')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('product_files');
    }
}
