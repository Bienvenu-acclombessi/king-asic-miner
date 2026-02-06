<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProfilController extends Controller
{
    /**
     * Display profile page
     */
    public function index()
    {
        return view('client.pages.account.profil');
    }

    /**
     * Update profile
     */
    public function update(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:20'],
        ]);

        $request->user()->update([
            'name' => $request->name,
            'phone' => $request->phone,
        ]);

        return redirect()->route('customer.profil.index')->with('success', 'Profil mis à jour avec succès');
    }
}
