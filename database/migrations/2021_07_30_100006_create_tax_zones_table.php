<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Database\Migration;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tax_zones', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('zone_type')->index();
            $table->string('price_display')->nullable();
            $table->boolean('active')->default(true)->index();
            $table->boolean('default')->default(false)->index();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tax_zones');
    }
};
