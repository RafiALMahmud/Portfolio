<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('order_number')->unique();
            $table->json('items'); // snapshot of items at purchase
            $table->decimal('subtotal', 12, 2);
            $table->decimal('shipping', 12, 2)->default(0);
            $table->decimal('grand_total', 12, 2);
            $table->enum('status', ['pending','paid','failed','cancelled','shipped','completed'])->default('pending');
            $table->boolean('flagged')->default(false);
            $table->string('payment_gateway')->nullable();
            $table->string('shipping_address')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
