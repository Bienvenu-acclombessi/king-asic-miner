<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Database\Migration;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->foreignId('brand_id')->after('id')
                ->nullable()
                ->constrained('brands');
        });

        Schema::table('products', function (Blueprint $table) {
            if (Schema::hasIndex('products', ['brand'])) {
                $table->dropIndex(['brand']);
            }
            $table->dropColumn('brand');
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            if ($this->canDropForeignKeys()) {
                $table->dropForeign(['brand_id']);
            }
            $table->dropColumn('brand_id');
        });
    }
};
