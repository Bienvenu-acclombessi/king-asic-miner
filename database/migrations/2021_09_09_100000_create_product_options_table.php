<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Database\Migration;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('product_options', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->json('name');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_options');
    }
};
