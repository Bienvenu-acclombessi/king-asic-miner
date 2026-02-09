<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('collection_groups', function (Blueprint $table) {
            $table->json('meta')->nullable()->after('handle');
        });

        Schema::table('collections', function (Blueprint $table) {
            $table->json('meta')->nullable()->after('attribute_data');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('collection_groups', function (Blueprint $table) {
            $table->dropColumn('meta');
        });

        Schema::table('collections', function (Blueprint $table) {
            $table->dropColumn('meta');
        });
    }
};
