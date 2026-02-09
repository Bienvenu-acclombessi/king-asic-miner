<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Add constraints to product columns after data has been migrated
     */
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            // Add unique constraint on slug
            $table->unique('slug');

            // Add index on name for faster searches
            $table->index('name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            // Drop indexes
            $table->dropUnique(['slug']);
            $table->dropIndex(['name']);
        });
    }
};
