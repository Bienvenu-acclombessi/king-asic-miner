<?php

namespace App\Models\Orders;

use App\Models\Configuration\Country;
use App\Models\Configuration\State;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CartAddress extends Model
{
    use HasFactory;

    protected $fillable = [
        'cart_id',
        'country_id',
        'state_id',
        'title',
        'first_name',
        'last_name',
        'company_name',
        'line_one',
        'line_two',
        'line_three',
        'city',
        'postcode',
        'delivery_instructions',
        'contact_email',
        'contact_phone',
        'type',
        'shipping_option',
        'meta',
        'tax_identifier',
    ];

    protected $casts = [
        'meta' => 'array',
    ];

    /**
     * Get the cart
     */
    public function cart(): BelongsTo
    {
        return $this->belongsTo(Cart::class);
    }

    /**
     * Get the country
     */
    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class);
    }

    /**
     * Get the state
     */
    public function state(): BelongsTo
    {
        return $this->belongsTo(State::class);
    }
}
