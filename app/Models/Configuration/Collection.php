<?php

namespace App\Models\Configuration;

use App\Models\Products\Brand;
use App\Models\Products\Product;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Collection extends Model
{
    use HasFactory;

    protected $fillable = [
        'collection_group_id',
        'slug',
        'attribute_data',
        'sort',
        'type',
        'meta',
    ];

    protected $casts = [
        'attribute_data' => 'array',
        'meta' => 'array',
    ];

    /**
     * Boot method to auto-generate slug
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($collection) {
            if (empty($collection->slug)) {
                $name = $collection->attribute_data['name'] ?? 'collection-' . time();
                $collection->slug = static::generateUniqueSlug($name);
            }
        });

        static::updating(function ($collection) {
            if ($collection->isDirty('attribute_data') && isset($collection->attribute_data['name'])) {
                $collection->slug = static::generateUniqueSlug($collection->attribute_data['name'], $collection->id);
            }
        });
    }

    /**
     * Generate unique slug
     */
    protected static function generateUniqueSlug($name, $ignoreId = null)
    {
        $slug = Str::slug($name);
        $originalSlug = $slug;
        $counter = 1;

        $query = static::where('slug', $slug);
        if ($ignoreId) {
            $query->where('id', '!=', $ignoreId);
        }

        while ($query->exists()) {
            $slug = $originalSlug . '-' . $counter;
            $counter++;
            $query = static::where('slug', $slug);
            if ($ignoreId) {
                $query->where('id', '!=', $ignoreId);
            }
        }

        return $slug;
    }

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
