<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Database\Migration;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('carts', function (Blueprint $table) {
            $table->foreignId('shipping_method_id')->nullable()->after('coupon_code')->constrained('shipping_methods')->nullOnDelete();
            $table->decimal('shipping_cost', 10, 2)->nullable()->after('shipping_method_id');
        });
    }

    public function down(): void
    {
        Schema::table('carts', function (Blueprint $table) {
            $table->dropForeign(['shipping_method_id']);
            $table->dropColumn(['shipping_method_id', 'shipping_cost']);
        });
    }
};
