<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Database\Migration;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('collection_customer_group', function (Blueprint $table) {
            $table->id();
            $table->foreignId('collection_id')->constrained('collections');
            $table->foreignId('customer_group_id')->constrained('customer_groups');
            $table->timestamp('starts_at')->nullable();
            $table->timestamp('ends_at')->nullable();
            $table->boolean('visible')->default(true)->index();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('collection_customer_group');
    }
};
