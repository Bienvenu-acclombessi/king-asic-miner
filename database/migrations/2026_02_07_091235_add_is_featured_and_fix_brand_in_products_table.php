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
        Schema::table('products', function (Blueprint $table) {
            // Add is_featured column
            $table->boolean('is_featured')->default(false)->after('status');

            // Check if brand column exists and rename it to brand_id
            if (Schema::hasColumn('products', 'brand')) {
                // Drop the old brand column
                $table->dropColumn('brand');
            }

            // Add brand_id as foreign key if it doesn't exist
            if (!Schema::hasColumn('products', 'brand_id')) {
                $table->foreignId('brand_id')->nullable()->after('product_type_id')->constrained('brands')->nullOnDelete();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            // Remove is_featured column
            $table->dropColumn('is_featured');

            // Remove brand_id foreign key and column
            if (Schema::hasColumn('products', 'brand_id')) {
                $table->dropForeign(['brand_id']);
                $table->dropColumn('brand_id');
            }

            // Restore old brand column
            $table->string('brand')->nullable()->index();
        });
    }
};
