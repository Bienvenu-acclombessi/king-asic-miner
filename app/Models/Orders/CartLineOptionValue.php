<?php

namespace App\Models\Orders;

use App\Models\Products\ProductOption;
use App\Models\Products\ProductOptionValue;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CartLineOptionValue extends Model
{
    protected $fillable = [
        'cart_line_id',
        'product_option_id',
        'product_option_value_id',
        'custom_value',
        'price_modifier',
        'price_type',
    ];

    protected $casts = [
        'price_modifier' => 'integer',
    ];

    /**
     * La ligne de panier parente
     */
    public function cartLine(): BelongsTo
    {
        return $this->belongsTo(CartLine::class);
    }

    /**
     * L'option sélectionnée
     */
    public function option(): BelongsTo
    {
        return $this->belongsTo(ProductOption::class, 'product_option_id');
    }

    /**
     * La valeur sélectionnée (null pour champs texte)
     */
    public function value(): BelongsTo
    {
        return $this->belongsTo(ProductOptionValue::class, 'product_option_value_id');
    }

    /**
     * Prix formaté (en euros/dollars)
     */
    public function getFormattedPriceAttribute(): float
    {
        return $this->price_modifier / 100;
    }

    /**
     * Récupère la valeur affichable (traduite ou custom)
     */
    public function getDisplayValue(?string $locale = null): string
    {
        if ($this->custom_value !== null) {
            return $this->custom_value;
        }

        return $this->value?->getTranslatedName($locale) ?? '';
    }

    /**
     * Calcule le prix pour cette option
     */
    public function calculatePrice(int $basePrice): int
    {
        if ($this->price_type === 'fixed') {
            return $this->price_modifier;
        }

        // Percentage
        return (int) ($basePrice * $this->price_modifier / 10000);
    }
}
