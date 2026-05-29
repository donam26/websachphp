<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();   // Customer
            $table->foreignId('book_id')->constrained()->cascadeOnDelete();   // Book
            $table->unsignedTinyInteger('rating');                            // 1..5
            $table->text('comment')->nullable();
            $table->boolean('is_verified_purchase')->default(false);
            $table->timestamps();                                             // created_at = ReviewDate

            $table->unique(['user_id', 'book_id']); // one review per customer per book
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};
