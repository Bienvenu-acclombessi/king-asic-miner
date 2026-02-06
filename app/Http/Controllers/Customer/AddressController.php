<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AddressController extends Controller
{
    /**
     * Display customer addresses
     */
    public function index()
    {
        return view('client.pages.account.address');
    }

    /**
     * Store a new address
     */
    public function store(Request $request)
    {
        // Logic to store address
        return redirect()->route('customer.address.index')->with('success', 'Adresse ajoutée avec succès');
    }

    /**
     * Update an address
     */
    public function update(Request $request, $id)
    {
        // Logic to update address
        return redirect()->route('customer.address.index')->with('success', 'Adresse mise à jour avec succès');
    }

    /**
     * Delete an address
     */
    public function destroy($id)
    {
        // Logic to delete address
        return redirect()->route('customer.address.index')->with('success', 'Adresse supprimée avec succès');
    }

    /**
     * Set default address
     */
    public function setDefault($id)
    {
        // Logic to set default address
        return redirect()->route('customer.address.index')->with('success', 'Adresse par défaut définie');
    }
}
