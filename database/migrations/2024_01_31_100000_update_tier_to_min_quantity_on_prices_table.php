<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Database\Migration;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('prices', function (Blueprint $table) {
            if (Schema::hasColumn('prices', 'tier')) {
                $table->renameColumn('tier', 'min_quantity');
            }
        });
    }

    public function down(): void
    {
        Schema::table('prices', function (Blueprint $table) {
            if (Schema::hasColumn('prices', 'min_quantity')) {
                $table->renameColumn('min_quantity', 'tier');
            }
        });
    }
};
