<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('stock_imports', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->foreignId('supplier_id')->nullable()->constrained('suppliers')->nullOnDelete();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->decimal('total_amount', 14, 2)->default(0);
            $table->text('note')->nullable();
            $table->enum('status', ['completed', 'cancelled'])->default('completed')->index();
            $table->timestamp('imported_at')->nullable();
            $table->timestamp('cancelled_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stock_imports');
    }
};
