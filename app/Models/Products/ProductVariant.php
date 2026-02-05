<?php

namespace App\Models\Products;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProductVariant extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'tax_class_id',
        'sku',
        'gtin',
        'mpn',
        'ean',
        'length_value',
        'length_unit',
        'width_value',
        'width_unit',
        'height_value',
        'height_unit',
        'weight_value',
        'weight_unit',
        'volume_value',
        'volume_unit',
        'shippable',
        'stock',
        'backorder',
        'purchasable',
        'attribute_data',
        'quantity_increment',
        'min_quantity',
    ];

    protected $casts = [
        'attribute_data' => 'array',
        'shippable' => 'boolean',
        'purchasable' => 'string',
        'backorder' => 'integer',
        'stock' => 'integer',
        'quantity_increment' => 'integer',
        'min_quantity' => 'integer',
    ];

    /**
     * Get the product
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Get prices
     */
    public function prices(): HasMany
    {
        return $this->hasMany(Price::class, 'priceable_id')
            ->where('priceable_type', 'product_variant');
    }

    /**
     * Get product option values
     */
    public function values(): BelongsToMany
    {
        return $this->belongsToMany(
            ProductOptionValue::class,
            'product_option_value_product_variant'
        );
    }
}
