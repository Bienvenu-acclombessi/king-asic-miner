<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Discounts\Discount;
use App\Models\Orders\Cart;
use App\Models\Orders\CartLine;
use App\Models\Orders\CartLineOptionValue;
use App\Models\Products\Product;
use App\Models\Products\ProductOption;
use App\Models\Products\ProductOptionValue;
use App\Models\Products\ProductVariant;
use App\Models\Shipping\ShippingMethod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class CartController extends Controller
{
    /**
     * Display cart page
     */
    public function index()
    {
        return view('client.pages.cart.index');
    }

    /**
     * Get cart data (API)
     */
    public function get()
    {
        $cart = $this->getCart();
        $cart->load(['lines.purchasable', 'lines.optionValues.productOption', 'lines.optionValues.productOptionValue']);

        $cartData = $this->formatCartData($cart);

        return response()->json([
            'success' => true,
            'cart' => $cartData,
        ]);
    }

    /**
     * Add item to cart
     */
    public function add(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'product_id' => 'required|exists:products,id',
            'variant_id' => 'nullable|exists:product_variants,id',
            'quantity' => 'required|integer|min:1',
            'options' => 'nullable|array',
            'options.*.option_id' => 'required|exists:product_options,id',
            'options.*.value_id' => 'required_without:options.*.custom_value|exists:product_option_values,id',
            'options.*.custom_value' => 'required_without:options.*.value_id|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Données invalides',
                'errors' => $validator->errors(),
            ], 422);
        }

        try {
            DB::beginTransaction();

            $product = Product::with(['productOptions', 'variants'])->findOrFail($request->product_id);

            // Check if product has required options and they are provided
            $requiredOptions = $product->productOptions()->wherePivot('required', true)->get();
            if ($requiredOptions->isNotEmpty() && empty($request->options)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Ce produit nécessite des options. Veuillez le configurer sur la page de détail.',
                    'redirect' => route('public.product.show', $product->slug),
                ], 400);
            }

            // Validate required options
            if (!empty($request->options)) {
                foreach ($requiredOptions as $requiredOption) {
                    $optionProvided = collect($request->options)->firstWhere('option_id', $requiredOption->id);
                    if (!$optionProvided) {
                        return response()->json([
                            'success' => false,
                            'message' => "L'option '{$requiredOption->getTranslatedLabel()}' est requise.",
                        ], 400);
                    }
                }
            }

            $cart = $this->getCart();

            // Determine purchasable (variant or product)
            if ($request->variant_id) {
                $purchasableType = ProductVariant::class;
                $purchasableId = $request->variant_id;
                $variant = ProductVariant::findOrFail($request->variant_id);

                // Check stock
                if ($variant->stock !== null && $variant->stock < $request->quantity) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Stock insuffisant pour cette variante.',
                    ], 400);
                }
            } else {
                $purchasableType = Product::class;
                $purchasableId = $product->id;
            }

            // Check if item with same options already exists in cart
            $existingLine = $this->findExistingCartLine($cart, $purchasableType, $purchasableId, $request->options);

            if ($existingLine) {
                // Update quantity
                $existingLine->quantity += $request->quantity;
                $existingLine->save();
                $cartLine = $existingLine;
            } else {
                // Create new cart line
                $cartLine = CartLine::create([
                    'cart_id' => $cart->id,
                    'purchasable_type' => $purchasableType,
                    'purchasable_id' => $purchasableId,
                    'quantity' => $request->quantity,
                ]);

                // Add option values
                if (!empty($request->options)) {
                    foreach ($request->options as $option) {
                        $optionValue = null;
                        $priceModifier = 0;
                        $priceType = 'fixed';

                        if (isset($option['value_id'])) {
                            $optionValue = ProductOptionValue::find($option['value_id']);
                            if ($optionValue) {
                                $priceModifier = $optionValue->price_modifier ?? 0;
                                $priceType = $optionValue->price_type ?? 'fixed';
                            }
                        }

                        CartLineOptionValue::create([
                            'cart_line_id' => $cartLine->id,
                            'product_option_id' => $option['option_id'],
                            'product_option_value_id' => $option['value_id'] ?? null,
                            'custom_value' => $option['custom_value'] ?? null,
                            'price_modifier' => $priceModifier,
                            'price_type' => $priceType,
                        ]);
                    }
                }
            }

            DB::commit();

            // Reload cart with relationships
            $cart->load(['lines.purchasable', 'lines.optionValues.productOption', 'lines.optionValues.productOptionValue']);
            $cartData = $this->formatCartData($cart);

            return response()->json([
                'success' => true,
                'message' => 'Produit ajouté au panier',
                'cart' => $cartData,
            ]);

        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de l\'ajout au panier: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Update cart line quantity
     */
    public function update(Request $request, $lineId)
    {
        $validator = Validator::make($request->all(), [
            'quantity' => 'required|integer|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        try {
            $cart = $this->getCart();
            $cartLine = CartLine::where('cart_id', $cart->id)
                ->where('id', $lineId)
                ->firstOrFail();

            if ($request->quantity == 0) {
                $cartLine->delete();
                $message = 'Produit retiré du panier';
            } else {
                $cartLine->quantity = $request->quantity;
                $cartLine->save();
                $message = 'Quantité mise à jour';
            }

            $cart->load(['lines.purchasable', 'lines.optionValues.productOption', 'lines.optionValues.productOptionValue']);
            $cartData = $this->formatCartData($cart);

            return response()->json([
                'success' => true,
                'message' => $message,
                'cart' => $cartData,
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la mise à jour: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Remove item from cart
     */
    public function remove($lineId)
    {
        try {
            $cart = $this->getCart();
            $cartLine = CartLine::where('cart_id', $cart->id)
                ->where('id', $lineId)
                ->firstOrFail();

            $cartLine->delete();

            $cart->load(['lines.purchasable', 'lines.optionValues.productOption', 'lines.optionValues.productOptionValue']);
            $cartData = $this->formatCartData($cart);

            return response()->json([
                'success' => true,
                'message' => 'Produit retiré du panier',
                'cart' => $cartData,
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la suppression: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Clear entire cart
     */
    public function clear()
    {
        try {
            $cart = $this->getCart();
            $cart->lines()->delete();

            return response()->json([
                'success' => true,
                'message' => 'Panier vidé',
                'cart' => $this->formatCartData($cart),
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors du vidage du panier: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Check if product can be added directly (no required options)
     */
    public function checkProduct($productId)
    {
        try {
            $product = Product::with(['productOptions' => function ($query) {
                $query->wherePivot('required', true);
            }])->findOrFail($productId);

            $hasRequiredOptions = $product->productOptions->isNotEmpty();

            return response()->json([
                'success' => true,
                'can_add_directly' => !$hasRequiredOptions,
                'product_url' => route('public.product.show', $product->slug),
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Produit non trouvé',
            ], 404);
        }
    }

    /**
     * Get or create cart for current user/session
     */
    protected function getCart(): Cart
    {
        // Get default currency and channel IDs
        $currencyId = $this->getDefaultCurrencyId();
        $channelId = $this->getDefaultChannelId();

        if (auth()->check()) {
            // User is logged in - get or create active cart (not completed)
            $cart = Cart::firstOrCreate(
                [
                    'user_id' => auth()->id(),
                    'completed_at' => null  // Only get non-completed carts
                ],
                [
                    'currency_id' => $currencyId,
                    'channel_id' => $channelId,
                ]
            );
        } else {
            // Guest user - use session cart
            $sessionCartId = Session::get('cart_id');

            if ($sessionCartId) {
                $cart = Cart::find($sessionCartId);
            }

            if (!isset($cart) || !$cart) {
                $cart = Cart::create([
                    'currency_id' => $currencyId,
                    'channel_id' => $channelId,
                ]);
                Session::put('cart_id', $cart->id);
            }
        }

        return $cart;
    }

    /**
     * Get default currency ID (create if doesn't exist)
     */
    protected function getDefaultCurrencyId(): int
    {
        try {
            $currency = DB::table('currencies')->first();

            if (!$currency) {
                // Create default currency
                $currencyId = DB::table('currencies')->insertGetId([
                    'code' => 'USD',
                    'name' => 'US Dollar',
                    'symbol' => '$',
                    'decimal_places' => 2,
                    'enabled' => true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
                return $currencyId;
            }

            return $currency->id;
        } catch (\Exception $e) {
            // If currencies table doesn't exist, return 1 as fallback
            return 1;
        }
    }

    /**
     * Get default channel ID (create if doesn't exist)
     */
    protected function getDefaultChannelId(): int
    {
        try {
            $channel = DB::table('channels')->first();

            if (!$channel) {
                // Create default channel
                $channelId = DB::table('channels')->insertGetId([
                    'name' => 'Web Store',
                    'handle' => 'web',
                    'default' => true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
                return $channelId;
            }

            return $channel->id;
        } catch (\Exception $e) {
            // If channels table doesn't exist, return 1 as fallback
            return 1;
        }
    }

    /**
     * Find existing cart line with same options
     */
    protected function findExistingCartLine($cart, $purchasableType, $purchasableId, $options)
    {
        $cartLines = CartLine::where('cart_id', $cart->id)
            ->where('purchasable_type', $purchasableType)
            ->where('purchasable_id', $purchasableId)
            ->with('optionValues')
            ->get();

        if ($cartLines->isEmpty()) {
            return null;
        }

        // If no options provided, match line with no options
        if (empty($options)) {
            return $cartLines->first(function ($line) {
                return $line->optionValues->isEmpty();
            });
        }

        // Find line with exact same options
        foreach ($cartLines as $line) {
            if ($this->optionsMatch($line->optionValues, $options)) {
                return $line;
            }
        }

        return null;
    }

    /**
     * Check if option values match
     */
    protected function optionsMatch($lineOptions, $requestOptions)
    {
        if ($lineOptions->count() !== count($requestOptions)) {
            return false;
        }

        foreach ($requestOptions as $reqOption) {
            $match = $lineOptions->first(function ($lineOption) use ($reqOption) {
                if ($lineOption->product_option_id != $reqOption['option_id']) {
                    return false;
                }

                if (isset($reqOption['value_id'])) {
                    return $lineOption->product_option_value_id == $reqOption['value_id'];
                } else {
                    return $lineOption->custom_value == ($reqOption['custom_value'] ?? null);
                }
            });

            if (!$match) {
                return false;
            }
        }

        return true;
    }

    /**
     * Apply coupon to cart
     */
    public function applyCoupon(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'coupon_code' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Code promo requis',
            ], 422);
        }

        try {
            $couponCode = strtoupper(trim($request->coupon_code));
            $cart = $this->getCart();

            // Find coupon
            $coupon = Discount::where('coupon', $couponCode)->first();

            if (!$coupon) {
                return response()->json([
                    'success' => false,
                    'message' => 'Code promo invalide',
                ], 404);
            }

            // Validate coupon
            if (!$coupon->isValid()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Ce code promo n\'est plus valide',
                ], 400);
            }

            // Calculate cart subtotal (in cents)
            $cart->load(['lines.purchasable', 'lines.optionValues.productOption', 'lines.optionValues.productOptionValue']);
            $cartData = $this->formatCartData($cart);
            $subtotal = $cartData['subtotal'];
            $itemCount = $cartData['items_count'];

            // Validate order conditions
            $errors = $coupon->validateOrderConditions($subtotal, $itemCount);
            if (!empty($errors)) {
                return response()->json([
                    'success' => false,
                    'message' => implode(' ', $errors),
                ], 400);
            }

            // Apply coupon to cart
            $cart->coupon_code = $couponCode;
            $cart->save();

            // Reload and format cart data with coupon
            $cartData = $this->formatCartData($cart);

            return response()->json([
                'success' => true,
                'message' => 'Code promo appliqué avec succès !',
                'cart' => $cartData,
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de l\'application du code promo: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Remove coupon from cart
     */
    public function removeCoupon()
    {
        try {
            $cart = $this->getCart();
            $cart->coupon_code = null;
            $cart->save();

            $cart->load(['lines.purchasable', 'lines.optionValues.productOption', 'lines.optionValues.productOptionValue']);
            $cartData = $this->formatCartData($cart);

            return response()->json([
                'success' => true,
                'message' => 'Code promo retiré',
                'cart' => $cartData,
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Format cart data for response
     */
    protected function formatCartData($cart)
    {
        $items = [];
        $subtotal = 0;

        foreach ($cart->lines as $line) {
            $purchasable = $line->purchasable;

            if (!$purchasable) {
                continue;
            }

            // Get base price
            if ($purchasable instanceof ProductVariant) {
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
                        $optionsPrice += ($price * $optionValue->price_modifier) / 10000; // Stored in basis points
                    } else {
                        $optionsPrice += $optionValue->price_modifier;
                    }
                }

                $optionName = $optionValue->productOption?->getTranslatedLabel() ?? '';
                $valueName = $optionValue->productOptionValue?->getTranslatedName() ?? $optionValue->custom_value;

                if ($optionName && $valueName) {
                    $optionsText[] = "{$optionName}: {$valueName}";
                }
            }

            $unitPrice = $price + $optionsPrice;
            $lineTotal = $unitPrice * $line->quantity;
            $subtotal += $lineTotal;

            $items[] = [
                'id' => $line->id,
                'product_id' => $product->id,
                'product_name' => $product->name,
                'product_slug' => $product->slug,
                'thumbnail' => $product->thumbnail_url,
                'variant_id' => $purchasable instanceof ProductVariant ? $purchasable->id : null,
                'sku' => $purchasable instanceof ProductVariant ? $purchasable->sku : null,
                'quantity' => $line->quantity,
                'unit_price' => $unitPrice / 100, // Convert from cents
                'line_total' => $lineTotal / 100,
                'options' => $optionsText,
            ];
        }

        $subtotalInDollars = $subtotal / 100;
        $discount = 0;
        $couponInfo = null;

        // Apply coupon if present
        if ($cart->coupon_code) {
            $coupon = Discount::where('coupon', $cart->coupon_code)->first();

            if ($coupon && $coupon->isValid()) {
                $discount = $coupon->calculateDiscount($subtotalInDollars);

                $couponInfo = [
                    'code' => $coupon->coupon,
                    'type' => $coupon->type,
                    'discount_value' => $coupon->discount_value,
                    'discount_amount' => $discount,
                ];
            }
        }

        // Get shipping cost
        $shippingCost = (float) ($cart->shipping_cost ?? 0);
        $shippingMethod = null;

        if ($cart->shipping_method_id && $cart->shippingMethod) {
            $shippingMethod = [
                'id' => $cart->shippingMethod->id,
                'name' => $cart->shippingMethod->name,
                'cost' => $shippingCost,
                'estimated_delivery' => $cart->shippingMethod->estimated_delivery,
            ];
        }

        $total = max(0, $subtotalInDollars - $discount + $shippingCost);

        return [
            'id' => $cart->id,
            'items' => $items,
            'items_count' => collect($items)->sum('quantity'),
            'subtotal' => $subtotalInDollars,
            'discount' => $discount,
            'coupon' => $couponInfo,
            'shipping_cost' => $shippingCost,
            'shipping_method' => $shippingMethod,
            'total' => $total,
        ];
    }

    /**
     * Get available shipping methods for cart
     */
    public function getShippingMethods()
    {
        try {
            $cart = $this->getCart();
            $cart->load(['lines.purchasable', 'lines.optionValues']);

            $cartData = $this->formatCartData($cart);
            $cartTotal = $cartData['subtotal'] - $cartData['discount'];

            // Get all active shipping methods
            $shippingMethods = ShippingMethod::active()
                ->ordered()
                ->get()
                ->filter(function ($method) use ($cartTotal) {
                    return $method->isAvailableForCart($cartTotal);
                })
                ->map(function ($method) use ($cartTotal) {
                    return [
                        'id' => $method->id,
                        'name' => $method->name,
                        'description' => $method->description,
                        'price_type' => $method->price_type,
                        'cost' => $method->calculateCost($cartTotal),
                        'estimated_delivery' => $method->estimated_delivery,
                    ];
                })
                ->values();

            return response()->json([
                'success' => true,
                'shipping_methods' => $shippingMethods,
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la récupération des méthodes de livraison: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Set shipping method for cart
     */
    public function setShippingMethod(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'shipping_method_id' => 'required|exists:shipping_methods,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Méthode de livraison invalide',
            ], 422);
        }

        try {
            $cart = $this->getCart();
            $shippingMethod = ShippingMethod::findOrFail($request->shipping_method_id);

            // Calculate cart totals
            $cart->load(['lines.purchasable', 'lines.optionValues']);
            $cartData = $this->formatCartData($cart);
            $cartTotal = $cartData['subtotal'] - $cartData['discount'];

            // Check if shipping method is available
            if (!$shippingMethod->isAvailableForCart($cartTotal)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cette méthode de livraison n\'est pas disponible pour votre panier',
                ], 400);
            }

            // Calculate shipping cost
            $shippingCost = $shippingMethod->calculateCost($cartTotal);

            // Update cart
            $cart->shipping_method_id = $shippingMethod->id;
            $cart->shipping_cost = $shippingCost;
            $cart->save();

            // Reload cart with shipping method
            $cart->load('shippingMethod');
            $cartData = $this->formatCartData($cart);

            return response()->json([
                'success' => true,
                'message' => 'Méthode de livraison mise à jour',
                'cart' => $cartData,
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur: ' . $e->getMessage(),
            ], 500);
        }
    }
}
