<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Database\Migration;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('product_option_value_product_variant', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('value_id')->constrained('product_option_values');
            $table->foreignId('variant_id')->constrained('product_variants');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_option_value_product_variant');
    }
};
