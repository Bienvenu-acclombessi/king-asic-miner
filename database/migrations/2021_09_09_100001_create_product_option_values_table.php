<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Database\Migration;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('product_option_values', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('product_option_id')->constrained('product_options');
            $table->json('name');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_option_values');
    }
};
