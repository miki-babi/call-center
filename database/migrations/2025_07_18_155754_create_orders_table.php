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
            $table->unsignedBigInteger('woo_customer_id');
            $table->foreign('woo_customer_id')
                    ->references('woo_customer_id')
                    ->on('customers')
                    ->onDelete('cascade');
            $table->foreignId('shop_id')->constrained('shops')->onDelete('cascade');
            $table->string('order-id');
            $table->string('order_number');
            $table->string('status', 20)->default('pending');
            $table->datetime('order_date');
            $table->decimal('total_amount', 10, 2);
            $table->string('payment_method', 50)->nullable();
            $table->string('payment_status', 20)->default('pending');
            $table->string('tracking_number', 100)->nullable();
            $table->text('customer_note')->nullable();
            $table->string('refund_status', 20)->default('none');
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
