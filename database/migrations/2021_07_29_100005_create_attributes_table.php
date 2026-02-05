<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Database\Migration;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('attributes', function (Blueprint $table) {
            $table->id();
            // $table->string('attribute_type')->index();
            $table->foreignId('attribute_group_id')->constrained('attribute_groups');
            $table->integer('position')->index();
            $table->json('name');
            // $table->string('handle');
            $table->string('handle', 100)->index(); // ou moins
            $table->string('attribute_type', 100);
            $table->string('section');
            $table->string('type')->index();
            $table->boolean('required');
            $table->string('default_value')->nullable();
            $table->json('configuration');
            $table->boolean('system');
            $table->timestamps();

            $table->unique(['attribute_type', 'handle']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('attributes');
    }
};
