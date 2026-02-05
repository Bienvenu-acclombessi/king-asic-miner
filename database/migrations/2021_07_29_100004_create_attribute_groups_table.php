<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Database\Migration;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('attribute_groups', function (Blueprint $table) {
            $table->id();
            $table->string('attributable_type')->index();
            $table->json('name');
            $table->string('handle')->unique();
            $table->integer('position')->index();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('attribute_groups');
    }
};
