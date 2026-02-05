<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Database\Migration;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('customer_group_discount', function (Blueprint $table) {
            $table->id();
            $table->foreignId('discount_id')->constrained('discounts');
            $table->foreignId('customer_group_id')->constrained('customer_groups');
            $table->timestamp('starts_at')->nullable();
            $table->timestamp('ends_at')->nullable();
            $table->boolean('visible')->default(true)->index();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('customer_group_discount');
    }
};
