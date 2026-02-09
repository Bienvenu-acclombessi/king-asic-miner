<?php

namespace App\Models\Products;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Facades\Storage;

class MinableCoin extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'symbol',
        'logo',
        'algorithm',
        'difficulty',
        'block_time',
        'block_reward',
        'default_price',
        'color',
        'is_active',
        'position',
    ];

    protected $casts = [
        'block_reward' => 'decimal:8',
        'default_price' => 'decimal:2',
        'is_active' => 'boolean',
        'block_time' => 'integer',
        'position' => 'integer',
    ];

    /**
     * Get products that can mine this coin
     */
    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'product_minable_coin')
            ->withPivot('position')
            ->withTimestamps()
            ->orderByPivot('position');
    }

    /**
     * Get logo URL
     * Example: $coin->logo_url returns "/storage/coins/btc.png"
     */
    public function getLogoUrlAttribute(): ?string
    {
        if ($this->logo) {
            return Storage::url($this->logo);
        }

        return null;
    }

    /**
     * Scope: Only active coins
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope: Order by position
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('position')->orderBy('name');
    }
}
