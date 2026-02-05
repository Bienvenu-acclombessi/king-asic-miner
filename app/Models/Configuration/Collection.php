<?php

namespace App\Models\Configuration;

use App\Models\Products\Brand;
use App\Models\Products\Product;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Collection extends Model
{
    use HasFactory;

    protected $fillable = [
        'collection_group_id',
        'attribute_data',
        'sort',
        'type',
    ];

    protected $casts = [
        'attribute_data' => 'array',
    ];

    /**
     * Get the collection group
     */
    public function group(): BelongsTo
    {
        return $this->belongsTo(CollectionGroup::class, 'collection_group_id');
    }

    /**
     * Get the parent collection
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(Collection::class, 'parent_id');
    }

    /**
     * Get the child collections
     */
    public function children(): HasMany
    {
        return $this->hasMany(Collection::class, 'parent_id');
    }

    /**
     * Get products
     */
    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'collection_product')
            ->withPivot('position')
            ->withTimestamps();
    }

    /**
     * Get brands
     */
    public function brands(): BelongsToMany
    {
        return $this->belongsToMany(Brand::class, 'brand_collection')
            ->withTimestamps();
    }
}
