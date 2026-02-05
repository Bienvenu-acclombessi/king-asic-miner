<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Database\Migration;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('carts', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('user_id')->nullable()->constrained('users');
            $table->foreignId('merged_id')->nullable()->constrained('carts');
            $table->foreignId('currency_id')->constrained('currencies');
            $table->foreignId('channel_id')->constrained('channels');
            $table->foreignId('order_id')->nullable()->constrained('orders');
            $table->string('coupon_code')->index()->nullable();
            $table->dateTime('completed_at')->nullable()->index();
            $table->json('meta')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('carts');
    }
};
