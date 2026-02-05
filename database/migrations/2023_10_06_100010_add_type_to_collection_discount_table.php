<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Database\Migration;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('collection_discount', function (Blueprint $table) {
            $table->string('type', 20)->after('collection_id')->default('limitation');
        });
    }

    public function down(): void
    {
        Schema::table('collection_discount', function (Blueprint $table) {
            $table->dropColumn('type');
        });
    }
};
