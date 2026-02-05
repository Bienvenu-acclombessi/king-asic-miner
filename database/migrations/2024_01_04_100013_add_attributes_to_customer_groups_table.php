<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Database\Migration;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('customer_groups', function (Blueprint $table) {
            $table->json('attribute_data')->after('default')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('customer_groups', function ($table) {
            $table->dropColumn('attribute_data');
        });
    }
};
