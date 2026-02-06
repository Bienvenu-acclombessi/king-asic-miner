<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Database\Migration;

return new class extends Migration
{
    public function up(): void
    {
        // Améliorer product_options
        Schema::table('product_options', function (Blueprint $table) {
            // Type d'affichage
            $table->enum('display_type', [
                'select',    // Liste déroulante
                'radio',     // Boutons radio
                'button',    // Boutons cliquables
                'color',     // Sélecteur de couleur
                'image',     // Sélection par image
                'swatch'     // Pastilles (couleur + texte)
            ])->default('select')->after('label');

            // Comportement
            $table->boolean('required')->default(false)->after('display_type');

            // Impact sur le produit
            $table->boolean('affects_price')->default(true)->after('shared');
            $table->boolean('affects_stock')->default(true)->after('affects_price');

            // Aide
            $table->text('help_text')->nullable()->after('affects_stock');
        });

        // Améliorer product_option_values
        Schema::table('product_option_values', function (Blueprint $table) {
            // Prix (en centimes)
            $table->integer('price_modifier')->default(0)->after('position');
            $table->enum('price_type', ['fixed', 'percentage'])->default('fixed')->after('price_modifier');

            // Visuels
            $table->string('image_path')->nullable()->after('price_type');
            $table->string('color_hex', 7)->nullable()->after('image_path');

            // Stock (si option gère stock distinct)
            $table->integer('stock_quantity')->nullable()->after('color_hex');

            // Disponibilité
            $table->boolean('is_available')->default(true)->after('stock_quantity');
            $table->boolean('is_default')->default(false)->after('is_available');
        });
    }

    public function down(): void
    {
        Schema::table('product_options', function (Blueprint $table) {
            $table->dropColumn([
                'display_type',
                'required',
                'shared',
                'affects_price',
                'affects_stock',
                'help_text',
            ]);
        });

        Schema::table('product_option_values', function (Blueprint $table) {
            $table->dropColumn([
                'price_modifier',
                'price_type',
                'image_path',
                'color_hex',
                'stock_quantity',
                'is_available',
                'is_default',
            ]);
        });
    }
};
