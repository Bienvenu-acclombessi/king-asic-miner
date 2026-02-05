<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Database\Migration;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('brand_collection', function (Blueprint $table) {
            $table->id();
            $table->foreignId('brand_id')->constrained('brands');
            $table->foreignId('collection_id')->constrained('collections');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('brand_collection');
    }
};
