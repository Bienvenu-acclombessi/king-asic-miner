<?php

namespace App\Models\Products;

use App\Models\Configuration\Collection;
use App\Models\Customers\CustomerGroup;
use App\Models\Orders\CartLine;
use App\Models\Orders\OrderLine;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Product extends Model implements HasMedia
{
    use HasFactory, SoftDeletes, InteractsWithMedia;

    protected $fillable = [
        'product_type_id',
        'brand_id',
        'status',
        'attribute_data',
    ];

    protected $casts = [
        'attribute_data' => 'array',
    ];

    /**
     * Get the product type
     */
    public function productType(): BelongsTo
    {
        return $this->belongsTo(ProductType::class);
    }

    /**
     * Get the brand
     */
    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class);
    }

    /**
     * Get all product variants
     */
    public function variants(): HasMany
    {
        return $this->hasMany(ProductVariant::class);
    }

    /**
     * Get product associations
     */
    public function associations(): HasMany
    {
        return $this->hasMany(ProductAssociation::class, 'product_parent_id');
    }

    /**
     * Get product options
     */
    public function productOptions(): BelongsToMany
    {
        return $this->belongsToMany(ProductOption::class, 'product_product_option')
            ->withPivot('position', 'display_type', 'required', 'affects_price', 'affects_stock')
            ->orderByPivot('position');
    }

    /**
     * Get collections
     */
    public function collections(): BelongsToMany
    {
        return $this->belongsToMany(Collection::class, 'collection_product')
            ->withPivot('position')
            ->withTimestamps();
    }

    /**
     * Get customer groups
     */
    public function customerGroups(): BelongsToMany
    {
        return $this->belongsToMany(CustomerGroup::class, 'customer_group_product')
            ->withPivot(['purchasable', 'visible', 'enabled', 'starts_at', 'ends_at'])
            ->withTimestamps();
    }

    /**
     * Get cart lines
     */
    public function cartLines(): HasMany
    {
        return $this->hasMany(CartLine::class, 'purchasable_id')
            ->where('purchasable_type', 'product');
    }

    /**
     * Get order lines
     */
    public function orderLines(): HasMany
    {
        return $this->hasMany(OrderLine::class, 'purchasable_id')
            ->where('purchasable_type', 'product');
    }
}
