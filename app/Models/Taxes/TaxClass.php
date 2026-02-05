<?php

namespace App\Models\Taxes;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TaxClass extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'default',
    ];

    protected $casts = [
        'default' => 'boolean',
    ];

    /**
     * Get tax rate amounts
     */
    public function taxRateAmounts(): HasMany
    {
        return $this->hasMany(TaxRateAmount::class);
    }
}
