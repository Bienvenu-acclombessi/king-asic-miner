<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Database\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('addresses', function (Blueprint $table) {
            $table->string('delivery_instructions', 1000)->nullable()->change();
        });

        Schema::table('cart_addresses', function (Blueprint $table) {
            $table->string('delivery_instructions', 1000)->nullable()->change();
        });

        Schema::table('order_addresses', function (Blueprint $table) {
            $table->string('delivery_instructions', 1000)->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('addresses', function (Blueprint $table) {
            $table->string('delivery_instructions')->nullable()->change();
        });

        Schema::table('cart_addresses', function (Blueprint $table) {
            $table->string('delivery_instructions')->nullable()->change();
        });

        Schema::table('order_addresses', function (Blueprint $table) {
            $table->string('delivery_instructions')->nullable()->change();
        });
    }
};
