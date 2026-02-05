<?php

namespace App\Models\Customers;

use App\Models\Orders\Cart;
use App\Models\Orders\Order;
use App\Models\Users\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'first_name',
        'last_name',
        'company_name',
        'tax_identifier',
        'account_ref',
        'attribute_data',
        'meta',
    ];

    protected $casts = [
        'attribute_data' => 'array',
        'meta' => 'array',
    ];

    /**
     * Get customer groups
     */
    public function customerGroups(): BelongsToMany
    {
        return $this->belongsToMany(CustomerGroup::class, 'customer_customer_group');
    }

    /**
     * Get users
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'customer_user');
    }

    /**
     * Get addresses
     */
    public function addresses(): HasMany
    {
        return $this->hasMany(Address::class);
    }

    /**
     * Get orders
     */
    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    /**
     * Get carts
     */
    public function carts(): HasMany
    {
        return $this->hasMany(Cart::class);
    }
}
