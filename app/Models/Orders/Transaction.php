<?php

namespace App\Models\Orders;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'success',
        'type',
        'driver',
        'amount',
        'reference',
        'status',
        'notes',
        'card_type',
        'last_four',
        'captured_at',
        'meta',
    ];

    protected $casts = [
        'success' => 'boolean',
        'amount' => 'integer',
        'meta' => 'array',
        'captured_at' => 'datetime',
    ];

    /**
     * Get the order
     */
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }
}
