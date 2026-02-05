<?php

namespace App\Models\Taxes;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TaxRateAmount extends Model
{
    use HasFactory;

    protected $fillable = [
        'tax_class_id',
        'tax_rate_id',
        'percentage',
    ];

    protected $casts = [
        'percentage' => 'decimal:3',
    ];

    /**
     * Get the tax class
     */
    public function taxClass(): BelongsTo
    {
        return $this->belongsTo(TaxClass::class);
    }

    /**
     * Get the tax rate
     */
    public function taxRate(): BelongsTo
    {
        return $this->belongsTo(TaxRate::class);
    }
}
