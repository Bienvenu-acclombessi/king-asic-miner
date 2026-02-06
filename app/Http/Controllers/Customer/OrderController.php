<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    /**
     * Display customer orders
     */
    public function index()
    {
        return view('client.pages.account.orders');
    }

    /**
     * Display order details
     */
    public function show($id)
    {
        return view('client.pages.account.order_details', compact('id'));
    }

    /**
     * Cancel an order
     */
    public function cancel($id)
    {
        // Logic to cancel order
        return redirect()->route('customer.orders.index')->with('success', 'Commande annulée avec succès');
    }

    /**
     * Reorder
     */
    public function reorder($id)
    {
        // Logic to reorder
        return redirect()->route('public.cart.index')->with('success', 'Produits ajoutés au panier');
    }
}
