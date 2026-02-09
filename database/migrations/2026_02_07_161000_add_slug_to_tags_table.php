<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use App\Models\Configuration\Tag;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tags', function (Blueprint $table) {
            $table->string('slug')->nullable()->after('id');
            $table->unique('slug');
        });

        $tags = Tag::all();
        foreach ($tags as $tag) {
            $slug = Str::slug($tag->value);
            $originalSlug = $slug;
            $counter = 1;

            while (Tag::where('slug', $slug)->where('id', '!=', $tag->id)->exists()) {
                $slug = $originalSlug . '-' . $counter++;
            }

            $tag->slug = $slug;
            $tag->save();
        }

        Schema::table('tags', function (Blueprint $table) {
            $table->string('slug')->nullable(false)->change();
        });
    }

    public function down(): void
    {
        Schema::table('tags', function (Blueprint $table) {
            $table->dropUnique(['slug']);
            $table->dropColumn('slug');
        });
    }
};
