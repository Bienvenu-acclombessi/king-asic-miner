<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use App\Models\Configuration\Collection;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Add slug column to collections table
        Schema::table('collections', function (Blueprint $table) {
            $table->string('slug')->nullable()->after('id');
            $table->unique('slug');
        });

        // Generate slugs for existing collections
        $collections = Collection::all();

        foreach ($collections as $collection) {
            $name = $collection->attribute_data['name'] ?? 'collection-' . $collection->id;
            $slug = Str::slug($name);

            // Ensure uniqueness
            $originalSlug = $slug;
            $counter = 1;

            while (Collection::where('slug', $slug)->where('id', '!=', $collection->id)->exists()) {
                $slug = $originalSlug . '-' . $counter;
                $counter++;
            }

            $collection->slug = $slug;
            $collection->save();
        }

        // Make slug non-nullable after populating
        Schema::table('collections', function (Blueprint $table) {
            $table->string('slug')->nullable(false)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('collections', function (Blueprint $table) {
            $table->dropUnique(['slug']);
            $table->dropColumn('slug');
        });
    }
};
