<?php

namespace App\Models\Taxes;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TaxZone extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'zone_type',
        'price_display',
        'active',
        'default',
    ];

    protected $casts = [
        'active' => 'boolean',
        'default' => 'boolean',
    ];

    /**
     * Get tax rates
     */
    public function taxRates(): HasMany
    {
        return $this->hasMany(TaxRate::class);
    }
}
