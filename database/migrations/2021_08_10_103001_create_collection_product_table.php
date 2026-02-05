<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Database\Migration;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('collection_product', function (Blueprint $table) {
            $table->id();
            $table->foreignId('collection_id')->constrained('collections');
            $table->foreignId('product_id')->constrained('products');
            $table->integer('position')->default(1)->index();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('collection_product');
    }
};
