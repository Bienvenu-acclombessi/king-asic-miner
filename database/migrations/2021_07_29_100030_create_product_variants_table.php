<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Database\Migration;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('product_variants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('products');
            $table->foreignId('tax_class_id')->constrained('tax_classes');
            $table->string('tax_ref')->index()->nullable();
            $table->integer('unit_quantity')->unsigned()->index()->default(1);
            $table->string('sku')->nullable()->index();
            $table->string('gtin')->nullable()->index();
            $table->string('mpn')->nullable()->index();
            $table->string('ean')->nullable()->index();
            // Dimensions
            $table->decimal('length_value', 10, 4)->nullable();
            $table->string('length_unit', 20)->nullable();
            $table->decimal('width_value', 10, 4)->nullable();
            $table->string('width_unit', 20)->nullable();
            $table->decimal('height_value', 10, 4)->nullable();
            $table->string('height_unit', 20)->nullable();
            $table->decimal('weight_value', 10, 4)->nullable();
            $table->string('weight_unit', 20)->nullable();
            $table->decimal('volume_value', 10, 4)->nullable();
            $table->string('volume_unit', 20)->nullable();
            $table->boolean('shippable')->default(true)->index();
            $table->integer('stock')->default(0)->index();
            $table->integer('backorder')->default(0)->index();
            $table->string('purchasable')->default('always')->index();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_variants');
    }
};
