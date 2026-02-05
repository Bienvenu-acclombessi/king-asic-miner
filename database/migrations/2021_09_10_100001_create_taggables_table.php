<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Database\Migration;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('taggables', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('tag_id')->constrained('tags');
            $table->morphs('taggable');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('taggables');
    }
};
