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
        Schema::create('product_minable_coin', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('products')->onDelete('cascade');
            $table->foreignId('minable_coin_id')->constrained('minable_coins')->onDelete('cascade');
            $table->integer('position')->default(0); // For ordering coins
            $table->timestamps();

            // Unique constraint to prevent duplicates
            $table->unique(['product_id', 'minable_coin_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_minable_coin');
    }
};
