<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Database\Migration;

return new class extends Migration
{
    public function up(): void
    {
        Schema::rename('discount_purchasables', 'discountables');

        Schema::table('discountables', function (Blueprint $table) {
            $table->renameColumn('purchasable_id', 'discountable_id');
        });

        Schema::table('discountables', function (Blueprint $table) {
            $table->renameColumn('purchasable_type', 'discountable_type');
        });
    }

    public function down(): void
    {
        Schema::rename('discountables', 'discount_purchasables');

        Schema::table('discount_purchasables', function (Blueprint $table) {
            $table->renameColumn('discountable_id', 'purchasable_id');
        });

        Schema::table('discount_purchasables', function (Blueprint $table) {
            $table->renameColumn('discountable_type', 'purchasable_type');
        });
    }
};
