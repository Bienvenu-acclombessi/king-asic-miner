<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Database\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('product_options', function (Blueprint $table) {
            $table->boolean('shared')->after('handle')->default(false)->index();
        });

        DB::table('product_options')->update([
            'shared' => true,
        ]);
    }

    public function down(): void
    {
        Schema::table('product_options', function (Blueprint $table) {
            $table->dropIndex(['shared']);
            $table->dropColumn('shared');
        });
    }
};
