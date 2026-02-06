<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Database\Migration;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cart_line_option_values', function (Blueprint $table) {
            $table->id();

            // Relation avec cart_line
            $table->foreignId('cart_line_id')
                ->constrained('cart_lines')
                ->onDelete('cascade');

            // Relation avec l'option et sa valeur
            $table->foreignId('product_option_id')
                ->constrained('product_options');

            $table->foreignId('product_option_value_id')
                ->nullable()
                ->constrained('product_option_values');

            // Pour les champs texte/textarea (custom_value)
            $table->text('custom_value')->nullable();

            // Snapshot du prix au moment de l'ajout au panier
            $table->integer('price_modifier')->default(0);
            $table->enum('price_type', ['fixed', 'percentage'])->default('fixed');

            $table->timestamps();

            // Index pour performances
            $table->index(['cart_line_id', 'product_option_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cart_line_option_values');
    }
};
