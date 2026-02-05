<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Database\Migration;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cart_lines', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('cart_id')->constrained('carts');
            $table->morphs('purchasable');
            $table->smallInteger('quantity')->unsigned();
            $table->json('meta')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cart_lines');
    }
};
