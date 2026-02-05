<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Database\Migration;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('carts', function (Blueprint $table) {
            $table->foreignId('customer_id')->after('user_id')
                ->nullable()
                ->constrained('customers');
        });
    }

    public function down(): void
    {
        Schema::table('carts', function (Blueprint $table) {
            if ($this->canDropForeignKeys()) {
                $table->dropForeign(['customer_id']);
            }
            $table->dropColumn('customer_id');
        });
    }
};
