<?php

namespace App\Models\Products;

use App\Models\Configuration\Collection;
use App\Models\Configuration\Tag;
use App\Models\Customers\CustomerGroup;
use App\Models\Orders\CartLine;
use App\Models\Orders\OrderLine;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'product_type_id',
        'brand_id',
        'status',
        'is_featured',
        'slug',
        'name',
        'short_description',
        'description',
        'seo_title',
        'seo_description',
        'seo_keywords',
        'thumbnail',
        'attribute_data',
    ];

    protected $casts = [
        'attribute_data' => 'array',
        'is_featured' => 'boolean',
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
            ->withPivot(['purchasable', 'visible', 'starts_at', 'ends_at'])
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

    /**
     * Get tags (polymorphic)
     */
    public function tags(): MorphToMany
    {
        return $this->morphToMany(Tag::class, 'taggable');
    }

    /**
     * Get minable coins
     */
    public function minableCoins(): BelongsToMany
    {
        return $this->belongsToMany(MinableCoin::class, 'product_minable_coin')
            ->withPivot('position')
            ->withTimestamps()
            ->orderByPivot('position');
    }

    /**
     * Get product attributes with their values
     */
    public function attributes(): BelongsToMany
    {
        return $this->belongsToMany(Attribute::class, 'attribute_product')
            ->withPivot('value')
            ->withTimestamps();
    }

    /**
     * Get product images (gallery)
     */
    public function images(): HasMany
    {
        return $this->hasMany(ProductImage::class)->ordered();
    }

    /*
    |--------------------------------------------------------------------------
    | Attribute Accessors - Custom Attributes (Technical Specs)
    |--------------------------------------------------------------------------
    */

    /**
     * Get custom attributes (technical specs)
     * Example: $product->custom_attributes returns ['hashrate' => '264T', 'power' => '5567W', ...]
     */
    public function getCustomAttributesAttribute(): array
    {
        return $this->attribute_data['custom_attributes'] ?? [];
    }

    /**
     * Get a specific custom attribute by handle
     * Example: $product->getCustomAttribute('hashrate') returns "264T"
     */
    public function getCustomAttribute(string $key, $default = null)
    {
        // First, try to get from the new attributes relation (pivot table)
        // Use getRelation() to avoid conflict with Eloquent's native $attributes property
        try {
            if ($this->relationLoaded('attributes')) {
                $attributesRelation = $this->getRelation('attributes');
                if ($attributesRelation) {
                    $attribute = $attributesRelation->firstWhere('handle', $key);
                    if ($attribute && isset($attribute->pivot->value)) {
                        return $attribute->pivot->value;
                    }
                }
            }
        } catch (\Exception $e) {
            // If relation doesn't exist, continue to fallback
        }

        // Fallback to old system (attribute_data)
        $attrs = $this->custom_attributes;
        $value = $attrs[$key] ?? $default;
        return is_array($value) ? ($value['en'] ?? $value[0] ?? $default) : $value;
    }

    /*
    |--------------------------------------------------------------------------
    | Attribute Accessors - Images
    |--------------------------------------------------------------------------
    */

    /**
     * Get thumbnail URL
     * Example: $product->thumbnail_url returns "/storage/products/thumbnails/xxx.jpg"
     */
    public function getThumbnailUrlAttribute(): ?string
    {
        if ($this->thumbnail) {
            return Storage::url($this->thumbnail);
        }

        return null;
    }

    /**
     * Check if product has thumbnail
     */
    public function hasThumbnail(): bool
    {
        return !empty($this->thumbnail);
    }

    /**
     * Check if product has gallery images
     */
    public function hasGalleryImages(): bool
    {
        return $this->images()->exists();
    }

    /*
    |--------------------------------------------------------------------------
    | Attribute Accessors - Brand
    |--------------------------------------------------------------------------
    */

    /**
     * Get brand name (handles multilingual)
     * Example: $product->brand_name returns "Bitmain"
     */
    public function getBrandNameAttribute(): string
    {
        if (!$this->brand) {
            return '';
        }

        $brandData = $this->brand->attribute_data;
        if (is_array($brandData)) {
            $name = $brandData['name'] ?? $this->brand->name ?? '';
            return is_array($name) ? ($name['en'] ?? $name[0] ?? '') : $name;
        }

        return $this->brand->name ?? '';
    }

    /*
    |--------------------------------------------------------------------------
    | Attribute Accessors - Technical Specifications
    |--------------------------------------------------------------------------
    */

    /**
     * Get voltage
     * Example: $product->voltage returns "380-415V"
     */
    public function getVoltageAttribute(): ?string
    {
        return $this->getCustomAttribute('voltage');
    }

    /**
     * Check if PSU is included
     * Example: $product->psu_included returns true/false
     */
    public function getPsuIncludedAttribute(): bool
    {
        $value = $this->getCustomAttribute('psu_included', false);
        return filter_var($value, FILTER_VALIDATE_BOOLEAN);
    }

    /**
     * Check if warranty is included
     * Example: $product->warranty_included returns true/false
     */
    public function getWarrantyIncludedAttribute(): bool
    {
        $value = $this->getCustomAttribute('warranty_included', true);
        return filter_var($value, FILTER_VALIDATE_BOOLEAN);
    }

    /**
     * Get warranty period
     * Example: $product->warranty_period returns "12 months"
     */
    public function getWarrantyPeriodAttribute(): ?string
    {
        return $this->getCustomAttribute('warranty_period');
    }

    /**
     * Get coolant flow
     * Example: $product->coolant_flow returns "8.0~10.0 L/min"
     */
    public function getCoolantFlowAttribute(): ?string
    {
        return $this->getCustomAttribute('coolant_flow');
    }

    /**
     * Get coolant pressure
     * Example: $product->coolant_pressure returns "≤3.5 bar"
     */
    public function getCoolantPressureAttribute(): ?string
    {
        return $this->getCustomAttribute('coolant_pressure');
    }

    /**
     * Get coolant PH value
     * Example: $product->coolant_ph_value
     */
    public function getCoolantPhValueAttribute(): ?string
    {
        return $this->getCustomAttribute('coolant_ph_value');
    }

    /**
     * Get working coolant
     * Example: $product->working_coolant returns "Antifreeze / Pure water / Deionized water"
     */
    public function getWorkingCoolantAttribute(): ?string
    {
        return $this->getCustomAttribute('working_coolant');
    }

    /**
     * Check if product is hydro cooled
     * Example: $product->is_hydro_cooled returns true/false
     */
    public function getIsHydroCooledAttribute(): bool
    {
        return !empty($this->coolant_flow) || !empty($this->coolant_pressure);
    }
}
