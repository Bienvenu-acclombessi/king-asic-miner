<?php

namespace App\Models\Taxes;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TaxRate extends Model
{
    use HasFactory;

    protected $fillable = [
        'tax_zone_id',
        'priority',
        'name',
    ];

    protected $casts = [
        'priority' => 'integer',
    ];

    /**
     * Get the tax zone
     */
    public function taxZone(): BelongsTo
    {
        return $this->belongsTo(TaxZone::class);
    }

    /**
     * Get tax rate amounts
     */
    public function taxRateAmounts(): HasMany
    {
        return $this->hasMany(TaxRateAmount::class);
    }
}
