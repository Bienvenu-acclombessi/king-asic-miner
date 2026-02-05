<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Database\Migration;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('discount_purchasables', function (Blueprint $table) {
            $table->id();
            $table->foreignId('discount_id')->constrained('discounts')->cascadeOnDelete();
            $table->morphs('purchasable', 'purchasable_idx');
            $table->string('type')->default('condition')->index();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('discount_purchasables');
    }
};
