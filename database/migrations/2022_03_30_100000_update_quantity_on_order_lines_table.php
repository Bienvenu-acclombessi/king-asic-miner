<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Database\Migration;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('order_lines', function (Blueprint $table) {
            $table->unsignedInteger('quantity')->change();
        });
    }

    public function down(): void
    {
        Schema::table('order_lines', function (Blueprint $table) {
            $table->smallInteger('quantity')->unsigned()->change();
        });
    }
};
