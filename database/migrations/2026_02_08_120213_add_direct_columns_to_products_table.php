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
            // Core product information (nullable at first, will be filled by migration)
            $table->string('slug')->nullable()->after('brand_id');
            $table->string('name')->nullable()->after('slug');
            $table->text('short_description')->nullable()->after('name');
            $table->text('description')->nullable()->after('short_description');

            // SEO fields
            $table->string('seo_title')->nullable()->after('description');
            $table->text('seo_description')->nullable()->after('seo_title');
            $table->string('seo_keywords')->nullable()->after('seo_description');

            // Main image
            $table->string('thumbnail')->nullable()->after('seo_keywords');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn([
                'slug',
                'name',
                'short_description',
                'description',
                'seo_title',
                'seo_description',
                'seo_keywords',
                'thumbnail',
            ]);
        });
    }
};
