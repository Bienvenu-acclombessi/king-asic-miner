<?php

namespace App\Models\Customers;

use App\Models\Configuration\Country;
use App\Models\Configuration\State;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Address extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
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
        'shipping_default',
        'billing_default',
        'meta',
        'tax_identifier',
    ];

    protected $casts = [
        'shipping_default' => 'boolean',
        'billing_default' => 'boolean',
        'meta' => 'array',
    ];

    /**
     * Get the customer
     */
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
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
