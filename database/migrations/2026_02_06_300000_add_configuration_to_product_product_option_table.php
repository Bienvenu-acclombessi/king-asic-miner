<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Database\Migration;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('product_product_option', function (Blueprint $table) {
            $table->string('display_type', 50)->default('select')->after('position');
            $table->boolean('required')->default(false)->after('display_type');
            $table->boolean('affects_price')->default(false)->after('required');
            $table->boolean('affects_stock')->default(false)->after('affects_price');
        });
    }

    public function down(): void
    {
        Schema::table('product_product_option', function (Blueprint $table) {
            $table->dropColumn(['display_type', 'required', 'affects_price', 'affects_stock']);
        });
    }
};
