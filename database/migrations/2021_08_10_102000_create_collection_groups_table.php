<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Database\Migration;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('collection_groups', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('handle')->index();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('collection_groups');
    }
};
