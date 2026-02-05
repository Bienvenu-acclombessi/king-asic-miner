<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Database\Migration;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('attributables', function (Blueprint $table) {
            $table->id();
            $table->morphs('attributable');
            $table->foreignId('attribute_id')->constrained('attributes');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('attributables');
    }
};
