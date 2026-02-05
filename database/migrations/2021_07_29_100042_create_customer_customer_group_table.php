<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Database\Migration;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('customer_customer_group', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained('customers');
            $table->foreignId('customer_group_id')->constrained('customer_groups');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('customer_customer_group');
    }
};
