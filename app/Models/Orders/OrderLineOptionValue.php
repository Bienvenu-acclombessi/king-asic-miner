<?php

namespace App\Models\Orders;

use App\Models\Products\ProductOption;
use App\Models\Products\ProductOptionValue;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderLineOptionValue extends Model
{
    protected $fillable = [
        'order_line_id',
        'product_option_id',
        'product_option_value_id',
        'option_name',
        'value_name',
        'custom_value',
        'price_modifier',
        'price_type',
    ];

    protected $casts = [
        'option_name' => 'array',
        'value_name' => 'array',
        'price_modifier' => 'integer',
    ];

    /**
     * La ligne de commande parente
     */
    public function orderLine(): BelongsTo
    {
        return $this->belongsTo(OrderLine::class);
    }

    /**
     * L'option (peut être null si supprimée)
     */
    public function option(): BelongsTo
    {
        return $this->belongsTo(ProductOption::class, 'product_option_id');
    }

    /**
     * La valeur (peut être null si supprimée)
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
     * Récupère le nom traduit de l'option (depuis snapshot)
     */
    public function getTranslatedOptionName(?string $locale = null): string
    {
        $locale = $locale ?? app()->getLocale();
        return $this->option_name[$locale] ?? $this->option_name['en'] ?? '';
    }

    /**
     * Récupère le nom traduit de la valeur (depuis snapshot)
     */
    public function getTranslatedValueName(?string $locale = null): string
    {
        if ($this->custom_value !== null) {
            return $this->custom_value;
        }

        $locale = $locale ?? app()->getLocale();
        return $this->value_name[$locale] ?? $this->value_name['en'] ?? '';
    }

    /**
     * Texte complet formaté pour affichage
     */
    public function getDisplayTextAttribute(): string
    {
        $optionName = $this->getTranslatedOptionName();
        $valueName = $this->getTranslatedValueName();

        return "{$optionName}: {$valueName}";
    }

    /**
     * Texte du modificateur de prix
     */
    public function getPriceModifierTextAttribute(): string
    {
        if ($this->price_modifier === 0) {
            return '';
        }

        $sign = $this->price_modifier > 0 ? '+' : '';
        $amount = $this->formatted_price;

        if ($this->price_type === 'percentage') {
            return "{$sign}{$amount}%";
        }

        return "{$sign}$" . number_format($amount, 2);
    }
}
