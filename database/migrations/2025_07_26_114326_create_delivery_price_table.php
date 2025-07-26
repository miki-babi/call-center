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
        Schema::create('delivery_price', function (Blueprint $table) {
            $table->id();
            $table->enum('mode_of_delivery', ['bicycle', 'motorbike', 'car']); 
            $table->string('base_price');
            $table->string('price_per_km');
            $table->string('max_weight');
            $table->string('max_distance');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('deliveries');
    }
};
