<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Database\Migration;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->foreignId('cart_id')->after('user_id')->nullable()->constrained('carts')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            if ($this->canDropForeignKeys()) {
                $table->dropForeign(['cart_id']);
            }
            $table->dropColumn('cart_id');
        });
    }
};
