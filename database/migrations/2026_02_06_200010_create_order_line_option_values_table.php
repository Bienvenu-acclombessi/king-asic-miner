<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Database\Migration;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('order_line_option_values', function (Blueprint $table) {
            $table->id();

            // Relation avec order_line
            $table->foreignId('order_line_id')
                ->constrained('order_lines')
                ->onDelete('cascade');

            // References (nullable car l'option peut être supprimée après la commande)
            $table->foreignId('product_option_id')
                ->nullable()
                ->constrained('product_options');

            $table->foreignId('product_option_value_id')
                ->nullable()
                ->constrained('product_option_values');

            // Snapshots pour préserver l'historique
            $table->json('option_name');           // {"en": "Batch", "fr": "Lot"}
            $table->json('value_name')->nullable(); // {"en": "March 2025", "fr": "Mars 2025"}

            // Pour les champs texte/textarea
            $table->text('custom_value')->nullable();

            // Snapshot du prix au moment de la commande
            $table->integer('price_modifier')->default(0);
            $table->enum('price_type', ['fixed', 'percentage'])->default('fixed');

            $table->timestamps();

            // Index pour performances
            $table->index('order_line_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_line_option_values');
    }
};
