<?php

namespace App\Models\Products;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProductOption extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'label',
        'handle',
        'display_type',
        'required',
        'shared',
        'affects_price',
        'affects_stock',
        'help_text',
        'position',
        'meta',
    ];

    protected $casts = [
        'name' => 'array',
        'label' => 'array',
        'required' => 'boolean',
        'shared' => 'boolean',
        'affects_price' => 'boolean',
        'affects_stock' => 'boolean',
        'position' => 'integer',
        'meta' => 'array',
    ];

    /**
     * Get products
     */
    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'product_product_option')
            ->withPivot('position')
            ->orderByPivot('position');
    }

    /**
     * Get option values
     */
    public function values(): HasMany
    {
        return $this->hasMany(ProductOptionValue::class)->orderBy('position');
    }

    /**
     * Vérifie si cette option crée des variants
     */
    public function createsVariants(): bool
    {
        return $this->affects_price || $this->affects_stock;
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
     * Récupère le label traduit
     */
    public function getTranslatedLabel(?string $locale = null): string
    {
        $locale = $locale ?? app()->getLocale();
        return $this->label[$locale] ?? $this->label['en'] ?? $this->getTranslatedName($locale);
    }
}
