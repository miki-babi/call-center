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
        Schema::create('sync_orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('shop_id')->constrained('shops')->onDelete('cascade');
            $table->string('last_order_id')->unique(); // Unique identifier for the order
            $table->enum('sync_status',['pending', 'in_progress', 'completed']); // 'pending', 'in_progress', 'completed
            $table->timestamp('last_synced_at'); // up to this datetime
            $table->string('status'); // or 'failed'
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sync_orders');
    }
};
