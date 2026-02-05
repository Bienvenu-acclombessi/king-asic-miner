<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Database\Migration;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('product_product_option', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('products');
            $table->foreignId('product_option_id')->constrained('product_options');
            $table->smallInteger('position')->index();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_product_option');
    }
};
