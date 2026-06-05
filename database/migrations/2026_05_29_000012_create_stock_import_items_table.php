<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('stock_import_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('stock_import_id')->constrained('stock_imports')->cascadeOnDelete();
            $table->foreignId('book_id')->nullable()->constrained('books')->nullOnDelete();
            $table->string('book_title');
            $table->unsignedInteger('quantity');
            $table->decimal('import_price', 12, 2)->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stock_import_items');
    }
};
