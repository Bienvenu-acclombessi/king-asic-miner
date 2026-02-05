<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Database\Migration;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('product_options', function (Blueprint $table) {
            $table->dropUnique(
                'product_options_handle_unique'
            );
        });

        Schema::table('product_options', function (Blueprint $table) {
            $table->index('handle');
        });
    }

    public function down(): void
    {
        Schema::table('product_options', function (Blueprint $table) {
            $table->dropIndex(
                'product_options_handle_index'
            );
        });

        Schema::table('product_options', function (Blueprint $table) {
            $table->unique('handle');
        });
    }
};
