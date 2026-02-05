<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Database\Migration;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('collection_groups', function (Blueprint $table) {
            $table->dropIndex(['handle']);
        });

        Schema::table('collection_groups', function (Blueprint $table) {
            $table->unique(['handle']);
        });
    }

    public function down(): void
    {
        Schema::table('collection_groups', function (Blueprint $table) {
            $table->dropUnique(['handle']);
        });

        Schema::table('collection_groups', function (Blueprint $table) {
            $table->index(['handle']);
        });
    }
};
