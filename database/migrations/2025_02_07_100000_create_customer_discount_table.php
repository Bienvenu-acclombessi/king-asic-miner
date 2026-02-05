<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Database\Migration;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('customer_discount', function (Blueprint $table) {
            $table->id();
            $table->foreignId('discount_id')->constrained('discounts');
            $table->foreignId('customer_id')->constrained('customers');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('customer_discount');
    }
};
