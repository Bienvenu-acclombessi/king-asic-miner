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
        Schema::create('minable_coins', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Bitcoin, Litecoin, etc.
            $table->string('symbol')->unique(); // BTC, LTC, etc.
            $table->string('logo')->nullable(); // Path to coin logo image
            $table->string('algorithm'); // SHA-256, Scrypt, etc.

            // Mining parameters for calculator
            $table->string('difficulty')->nullable(); // Network difficulty (stored as string for big numbers)
            $table->integer('block_time')->nullable(); // Block time in seconds
            $table->decimal('block_reward', 20, 8)->nullable(); // Block reward
            $table->decimal('default_price', 20, 2)->default(0); // Default price in USD

            // Meta data
            $table->string('color')->nullable(); // Brand color for UI
            $table->boolean('is_active')->default(true);
            $table->integer('position')->default(0); // For ordering

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('minable_coins');
    }
};
