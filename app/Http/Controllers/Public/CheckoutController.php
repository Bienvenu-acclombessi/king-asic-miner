<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Configuration\Country;
use App\Models\Customers\Address;
use App\Models\Customers\Customer;
use App\Models\Orders\Cart;
use App\Models\Orders\CartAddress;
use App\Models\Orders\Order;
use App\Models\Orders\OrderLine;
use App\Models\Orders\OrderAddress;
use App\Models\Shipping\ShippingMethod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CheckoutController extends Controller
{
    /**
     * Display checkout page
     */
    public function index()
    {
        $user = auth()->user();

        // Get or create customer for this user
        $customer = $user->customers()->first();
        if (!$customer) {
            $customer = Customer::create([
                'first_name' => $user->first_name,
                'last_name' => $user->last_name,
            ]);
            $user->customers()->attach($customer->id);
        }

        // Get user's cart
        $cart = Cart::where('user_id', $user->id)
            ->whereNull('completed_at')
            ->with([
                'lines.purchasable',
                'lines.optionValues.productOption',
                'lines.optionValues.productOptionValue',
                'shippingAddress',
                'billingAddress',
                'shippingMethod'
            ])
            ->first();

        // If no cart or empty cart, redirect to cart page
        if (!$cart || $cart->lines->count() === 0) {
            return redirect()->route('public.cart.index')->with('error', 'Votre panier est vide.');
        }

        // Get customer addresses
        $addresses = $customer->addresses()->get();

        // Get available shipping methods
        $cartData = $this->formatCartForCheckout($cart);
        $cartTotal = $cartData['subtotal'];

        $shippingMethods = ShippingMethod::active()
            ->ordered()
            ->get()
            ->filter(function ($method) use ($cartTotal) {
                return $method->isAvailableForCart($cartTotal);
            });

        // Get countries for address form
        $countries = Country::orderBy('name')->get();

        return view('client.pages.checkout.index', compact('cart', 'addresses', 'shippingMethods', 'customer', 'cartData', 'countries'));
    }

    /**
     * Add a new address during checkout
     */
    public function addAddress(Request $request)
    {
        try {
            $validated = $request->validate([
                'first_name' => 'required|string|max:255',
                'last_name' => 'required|string|max:255',
                'line_one' => 'required|string|max:255',
                'line_two' => 'nullable|string|max:255',
                'city' => 'required|string|max:255',
                'postcode' => 'required|string|max:20',
                'contact_phone' => 'required|string|max:50',
                'contact_email' => 'nullable|email|max:255',
                'country_id' => 'required|exists:countries,id',
                'state_id' => 'nullable',
                'company_name' => 'nullable|string|max:255',
                'shipping_default' => 'nullable|boolean',
                'type' => 'required|in:shipping,billing',
            ]);

            $user = auth()->user();
            $customer = $user->customers()->first();

            if (!$customer) {
                return response()->json([
                    'success' => false,
                    'message' => 'Profil client non trouvé'
                ], 404);
            }

            // Remove state_id if it's empty or not a number
            if (empty($validated['state_id']) || !is_numeric($validated['state_id'])) {
                unset($validated['state_id']);
            }

            // Set default flags based on type
            if ($validated['type'] === 'shipping') {
                $validated['shipping_default'] = $validated['shipping_default'] ?? false;
            } elseif ($validated['type'] === 'billing') {
                $validated['billing_default'] = $validated['shipping_default'] ?? false;
                unset($validated['shipping_default']);
            }

            $address = $customer->addresses()->create($validated);

            return response()->json([
                'success' => true,
                'message' => 'Adresse ajoutée avec succès',
                'address' => $address->load('country')
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur de validation',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de l\'ajout de l\'adresse: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Select an address for the cart
     */
    public function selectAddress(Request $request)
    {
        $request->validate([
            'address_id' => 'required|exists:addresses,id',
            'type' => 'required|in:shipping,billing',
        ]);

        $user = auth()->user();
        $cart = Cart::where('user_id', $user->id)
            ->whereNull('completed_at')
            ->first();

        if (!$cart) {
            return response()->json(['success' => false, 'message' => 'Panier introuvable'], 404);
        }

        $address = Address::findOrFail($request->address_id);

        // Delete existing cart address of this type
        CartAddress::where('cart_id', $cart->id)
            ->where('type', $request->type)
            ->delete();

        // Create new cart address
        CartAddress::create([
            'cart_id' => $cart->id,
            'type' => $request->type,
            'first_name' => $address->first_name,
            'last_name' => $address->last_name,
            'company_name' => $address->company_name,
            'line_one' => $address->line_one,
            'line_two' => $address->line_two,
            'line_three' => $address->line_three,
            'city' => $address->city,
            'postcode' => $address->postcode,
            'delivery_instructions' => $address->delivery_instructions,
            'contact_email' => $address->contact_email,
            'contact_phone' => $address->contact_phone,
            'country_id' => $address->country_id,
            'state_id' => $address->state_id,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Adresse sélectionnée avec succès'
        ]);
    }

    /**
     * Place order
     */
    public function placeOrder(Request $request)
    {
        $request->validate([
            'payment_method' => 'required|in:coinpal,bank_transfer',
            'terms_accepted' => 'required|accepted',
            'shipping_address_id' => 'required|exists:addresses,id',
        ]);

        $user = auth()->user();
        $customer = $user->customers()->first();

        if (!$customer) {
            return response()->json(['success' => false, 'message' => 'Customer not found'], 404);
        }

        $cart = Cart::where('user_id', $user->id)
            ->whereNull('completed_at')
            ->with(['lines.purchasable', 'lines.optionValues', 'shippingMethod'])
            ->first();

        if (!$cart || $cart->lines->count() === 0) {
            return response()->json(['success' => false, 'message' => 'Panier vide'], 400);
        }

        try {
            DB::beginTransaction();

            // Calculate totals using the cart formatter
            $cartData = $this->formatCartForCheckout($cart);

            $subTotal = $cartData['subtotal'];
            $shippingTotal = $cartData['shipping_cost'];
            $discountTotal = $cartData['discount'];
            $taxTotal = 0;
            $total = $cartData['total'];

            // Create order
            $order = Order::create([
                'user_id' => $user->id,
                'customer_id' => $customer->id,
                'cart_id' => $cart->id,
                'channel_id' => 1, // Default channel
                'status' => $request->payment_method === 'bank_transfer' ? 'pending_payment' : 'pending',
                'payment_method' => $request->payment_method,
                'payment_status' => $request->payment_method === 'bank_transfer' ? 'pending' : 'pending',
                'reference' => 'ORD-' . strtoupper(Str::random(10)),
                'sub_total' => $subTotal * 100, // Store in cents
                'discount_total' => $discountTotal * 100,
                'shipping_total' => $shippingTotal * 100,
                'tax_total' => $taxTotal * 100,
                'total' => $total * 100,
                'currency_code' => 'USD',
                'tax_breakdown' => [],
                'discount_breakdown' => [],
                'shipping_breakdown' => [],
                'placed_at' => now(),
                'new_customer' => $customer->orders()->count() === 0,
            ]);

            // Create order lines
            foreach ($cartData['items'] as $item) {
                $purchasable = $item['purchasable'];
                $product = $item['product'];

                OrderLine::create([
                    'order_id' => $order->id,
                    'purchasable_type' => get_class($purchasable),
                    'purchasable_id' => $purchasable->id,
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['unit_price'] * 100, // Convert to cents
                    'sub_total' => $item['line_total'] * 100,
                    'tax_total' => 0,
                    'total' => $item['line_total'] * 100,
                    'description' => $product->name,
                    'identifier' => $product->sku ?? $product->slug,
                    'meta' => [
                        'options' => $item['options'],
                    ],
                ]);
            }

            // Create order addresses
            $shippingAddress = Address::findOrFail($request->shipping_address_id);

            // Get state name if state_id exists
            $stateName = null;
            if ($shippingAddress->state_id && $shippingAddress->state) {
                $stateName = $shippingAddress->state->name ?? null;
            }

            OrderAddress::create([
                'order_id' => $order->id,
                'type' => 'shipping',
                'first_name' => $shippingAddress->first_name,
                'last_name' => $shippingAddress->last_name,
                'company_name' => $shippingAddress->company_name,
                'line_one' => $shippingAddress->line_one,
                'line_two' => $shippingAddress->line_two,
                'line_three' => $shippingAddress->line_three,
                'city' => $shippingAddress->city,
                'state' => $stateName,
                'postcode' => $shippingAddress->postcode,
                'contact_email' => $shippingAddress->contact_email,
                'contact_phone' => $shippingAddress->contact_phone,
                'country_id' => $shippingAddress->country_id,
            ]);

            // Also create billing address (same as shipping for now)
            OrderAddress::create([
                'order_id' => $order->id,
                'type' => 'billing',
                'first_name' => $shippingAddress->first_name,
                'last_name' => $shippingAddress->last_name,
                'company_name' => $shippingAddress->company_name,
                'line_one' => $shippingAddress->line_one,
                'line_two' => $shippingAddress->line_two,
                'line_three' => $shippingAddress->line_three,
                'city' => $shippingAddress->city,
                'state' => $stateName,
                'postcode' => $shippingAddress->postcode,
                'contact_email' => $shippingAddress->contact_email,
                'contact_phone' => $shippingAddress->contact_phone,
                'country_id' => $shippingAddress->country_id,
            ]);

            // Mark cart as completed and clear cart lines
            $cart->update(['completed_at' => now()]);

            // Delete all cart lines since they're now in the order
            $cart->lines()->delete();

            DB::commit();

            // TODO: Send email notification based on payment method
            if ($request->payment_method === 'bank_transfer') {
                // Send email with bank transfer instructions
            }

            return response()->json([
                'success' => true,
                'message' => $request->payment_method === 'bank_transfer'
                    ? 'Commande créée avec succès. Vous recevrez un email avec les instructions de paiement.'
                    : 'Commande créée avec succès.',
                'order_id' => $order->id,
                'order_reference' => $order->reference,
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => 'Erreur lors de la création de la commande: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Payment page for pending orders
     */
    public function payment($orderId)
    {
        $order = Order::where('user_id', auth()->id())
            ->with(['lines.purchasable', 'addresses'])
            ->findOrFail($orderId);

        // Check if payment is needed
        if ($order->payment_status !== 'pending') {
            return redirect()->route('customer.orders.show', $order->id)
                ->with('info', 'Cette commande a déjà été payée ou ne nécessite pas de paiement.');
        }

        // Only CoinPal orders should go through this flow
        if ($order->payment_method !== 'coinpal') {
            return redirect()->route('customer.orders.show', $order->id)
                ->with('info', 'Cette méthode de paiement ne nécessite pas de finalisation en ligne.');
        }

        return view('client.pages.checkout.payment', compact('order'));
    }

    /**
     * Format cart data for checkout
     */
    private function formatCartForCheckout($cart)
    {
        $items = [];
        $subtotal = 0;

        foreach ($cart->lines as $line) {
            $purchasable = $line->purchasable;

            if (!$purchasable) {
                continue;
            }

            // Get product from purchasable
            if ($purchasable instanceof \App\Models\Products\ProductVariant) {
                $product = $purchasable->product;
                $price = $purchasable->prices()->first()->price ?? 0;
            } else {
                $product = $purchasable;
                $price = $product->variants()->first()?->prices()->first()->price ?? 0;
            }

            // Apply option modifiers
            $optionsPrice = 0;
            $optionsText = [];

            foreach ($line->optionValues as $optionValue) {
                if ($optionValue->price_modifier != 0) {
                    if ($optionValue->price_type === 'percentage') {
                        $optionsPrice += ($price * $optionValue->price_modifier) / 10000;
                    } else {
                        $optionsPrice += $optionValue->price_modifier;
                    }
                }

                $optionName = $optionValue->productOption?->name ?? '';
                $valueName = $optionValue->productOptionValue?->value ?? $optionValue->custom_value;

                if ($optionName && $valueName) {
                    $optionsText[] = "{$optionName}: {$valueName}";
                }
            }

            $unitPrice = $price + $optionsPrice;
            $lineTotal = $unitPrice * $line->quantity;
            $subtotal += $lineTotal;

            $items[] = [
                'line_id' => $line->id,
                'product' => $product,
                'purchasable' => $purchasable,
                'quantity' => $line->quantity,
                'unit_price' => $unitPrice / 100, // Convert from cents to dollars
                'line_total' => $lineTotal / 100,
                'options' => $optionsText,
            ];
        }

        $subtotalInDollars = $subtotal / 100;
        $discount = 0;

        // Apply coupon if present
        if ($cart->coupon_code) {
            $coupon = \App\Models\Discounts\Discount::where('coupon', $cart->coupon_code)->first();
            if ($coupon && $coupon->isValid()) {
                $discount = $coupon->calculateDiscount($subtotalInDollars);
            }
        }

        $shippingCost = (float) ($cart->shipping_cost ?? 0);
        $total = max(0, $subtotalInDollars - $discount + $shippingCost);

        return [
            'items' => $items,
            'subtotal' => $subtotalInDollars,
            'discount' => $discount,
            'shipping_cost' => $shippingCost,
            'total' => $total,
        ];
    }
}
