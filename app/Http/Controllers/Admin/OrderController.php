<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Orders\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::with(['user', 'customer', 'lines'])
            ->orderBy('created_at', 'desc');

        // Filter by status
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        // Filter by payment status
        if ($request->has('payment_status') && $request->payment_status != '') {
            $query->where('payment_status', $request->payment_status);
        }

        // Search by reference
        if ($request->has('search') && $request->search != '') {
            $query->where(function ($q) use ($request) {
                $q->where('reference', 'like', '%' . $request->search . '%')
                  ->orWhere('customer_reference', 'like', '%' . $request->search . '%');
            });
        }

        $orders = $query->paginate(20);

        return view('admin.pages.orders.index', compact('orders'));
    }

    public function show($id)
    {
        $order = Order::with([
            'user',
            'customer',
            'lines.purchasable.images',
            'addresses.country',
            'addresses.state',
            'transactions'
        ])->findOrFail($id);

        return view('admin.pages.orders.show', compact('order'));
    }

    public function edit($id)
    {
        $order = Order::with(['lines.purchasable', 'addresses'])
            ->findOrFail($id);

        return view('admin.pages.orders.edit', compact('order'));
    }

    public function update(Request $request, $id)
    {
        $order = Order::findOrFail($id);

        $validated = $request->validate([
            'status' => 'required|in:pending,processing,shipped,completed,cancelled',
            'payment_status' => 'required|in:pending,paid,failed,refunded',
            'notes' => 'nullable|string',
        ]);

        $order->update($validated);

        return redirect()->route('admin.orders.show', $id)
            ->with('success', 'Commande mise à jour avec succès');
    }
}
