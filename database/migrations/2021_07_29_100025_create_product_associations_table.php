<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Database\Migration;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('product_associations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_parent_id')->constrained('products');
            $table->foreignId('product_target_id')->constrained('products');
            $table->string('type')->index();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_associations');
    }
};
