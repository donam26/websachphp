<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->decimal('subtotal', 12, 2)->default(0);
            $table->decimal('total_amount', 12, 2);
            $table->string('shipping_name');
            $table->string('shipping_phone');
            $table->text('shipping_address');
            $table->text('note')->nullable();
            $table->enum('status', ['pending', 'confirmed', 'shipping', 'completed', 'cancelled'])
                ->default('pending')
                ->index();
            // String (không phải enum) để admin thêm phương thức thanh toán động.
            // Giá trị là 'code' tham chiếu bảng payment_methods.
            $table->string('payment_method')->default('cod')->index();
            $table->enum('payment_status', ['pending', 'paid', 'failed', 'refunded'])->default('pending');
            $table->string('payment_ref')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->timestamp('cancelled_at')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('orders');
    }
}; 