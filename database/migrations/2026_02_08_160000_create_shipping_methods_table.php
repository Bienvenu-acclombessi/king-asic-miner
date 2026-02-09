<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Database\Migration;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('shipping_methods', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('handle')->unique();
            $table->text('description')->nullable();

            // Price configuration
            $table->enum('price_type', ['fixed', 'percentage', 'free'])->default('fixed');
            $table->decimal('price', 10, 2)->default(0); // Price in dollars

            // Conditions
            $table->decimal('min_order_amount', 10, 2)->nullable();
            $table->decimal('max_order_amount', 10, 2)->nullable();
            $table->decimal('max_weight', 10, 2)->nullable(); // kg

            // Delivery information
            $table->integer('estimated_days_min')->nullable();
            $table->integer('estimated_days_max')->nullable();

            // Status and ordering
            $table->boolean('is_active')->default(true);
            $table->integer('display_order')->default(0);

            // Metadata
            $table->json('meta')->nullable();

            $table->timestamps();

            // Indexes
            $table->index('is_active');
            $table->index('display_order');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('shipping_methods');
    }
};
