<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Orders\Order;
use App\Models\Orders\Cart;
use App\Models\Orders\CartLine;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    /**
     * Display customer orders
     */
    public function index()
    {
        $orders = Order::where('user_id', auth()->id())
            ->with(['lines.purchasable', 'addresses'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('client.pages.account.orders', compact('orders'));
    }

    /**
     * Display order details
     */
    public function show($id)
    {
        $order = Order::where('user_id', auth()->id())
            ->with(['lines.purchasable.images', 'addresses.country', 'addresses.state', 'transactions'])
            ->findOrFail($id);

        return view('client.pages.account.order_details', compact('order'));
    }

    /**
     * Cancel an order
     */
    public function cancel($id)
    {
        $order = Order::where('user_id', auth()->id())->findOrFail($id);

        // Can only cancel orders that are not shipped or completed
        if (in_array($order->status, ['shipped', 'completed', 'cancelled'])) {
            return redirect()->route('customer.orders.show', $id)
                ->with('error', 'Cette commande ne peut pas être annulée.');
        }

        $order->update(['status' => 'cancelled']);

        return redirect()->route('customer.orders.index')
            ->with('success', 'Commande annulée avec succès');
    }

    /**
     * Reorder
     */
    public function reorder($id)
    {
        $order = Order::where('user_id', auth()->id())
            ->with('lines.purchasable')
            ->findOrFail($id);

        // Get or create cart
        $cart = Cart::firstOrCreate(
            [
                'user_id' => auth()->id(),
                'merged_id' => null,
                'channel_id' => $order->channel_id,
                'completed_at' => null,
            ],
            [
                'currency_id' => 1, // Default currency
                'meta' => [],
            ]
        );

        // Add order items to cart
        foreach ($order->lines as $line) {
            if ($line->purchasable) {
                CartLine::create([
                    'cart_id' => $cart->id,
                    'purchasable_type' => $line->purchasable_type,
                    'purchasable_id' => $line->purchasable_id,
                    'quantity' => $line->quantity,
                    'meta' => $line->meta,
                ]);
            }
        }

        return redirect()->route('public.cart.index')
            ->with('success', 'Produits ajoutés au panier');
    }
}
