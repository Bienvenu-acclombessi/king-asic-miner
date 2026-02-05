<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Database\Migration;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tax_rate_amounts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tax_class_id')->nullable()->constrained('tax_classes');
            $table->foreignId('tax_rate_id')->nullable()->constrained('tax_rates');
            $table->decimal('percentage', 7, 3)->index();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tax_rate_amounts');
    }
};
