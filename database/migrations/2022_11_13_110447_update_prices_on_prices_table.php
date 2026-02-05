<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Database\Migration;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('prices', function (Blueprint $table) {
            $table->unsignedBigInteger('price')->change();
            $table->unsignedBigInteger('compare_price')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('prices', function (Blueprint $table) {
            $table->unsignedInteger('price')->change();
            $table->unsignedInteger('compare_price')->change();
        });
    }
};
