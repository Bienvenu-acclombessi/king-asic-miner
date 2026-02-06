<?php

namespace App\Models\Products;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class ProductOptionValue extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_option_id',
        'name',
        'position',
        'price_modifier',
        'price_type',
        'image_path',
        'color_hex',
        'stock_quantity',
        'is_available',
        'is_default',
        'meta',
    ];

    protected $casts = [
        'name' => 'array',
        'position' => 'integer',
        'price_modifier' => 'integer',
        'stock_quantity' => 'integer',
        'is_available' => 'boolean',
        'is_default' => 'boolean',
        'meta' => 'array',
    ];

    /**
     * Get the product option
     */
    public function productOption(): BelongsTo
    {
        return $this->belongsTo(ProductOption::class);
    }

    /**
     * Get product variants
     */
    public function variants(): BelongsToMany
    {
        return $this->belongsToMany(
            ProductVariant::class,
            'product_option_value_product_variant',
            'value_id',
            'variant_id'
        );
    }

    /**
     * Récupère le nom traduit
     */
    public function getTranslatedName(?string $locale = null): string
    {
        $locale = $locale ?? app()->getLocale();
        return $this->name[$locale] ?? $this->name['en'] ?? '';
    }

    /**
     * Prix formaté (en euros/dollars)
     */
    public function getFormattedPriceAttribute(): float
    {
        return $this->price_modifier / 100;
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

    /**
     * Vérifie si cette valeur a une image
     */
    public function hasImage(): bool
    {
        return !empty($this->image_path);
    }

    /**
     * Vérifie si cette valeur a une couleur
     */
    public function hasColor(): bool
    {
        return !empty($this->color_hex);
    }

    /**
     * Vérifie si cette valeur est en stock
     */
    public function inStock(): bool
    {
        if (!$this->productOption->affects_stock) {
            return true;
        }

        return $this->stock_quantity === null || $this->stock_quantity > 0;
    }
}
