<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AccountController extends Controller
{
    /**
     * Display dashboard
     */
    public function dashboard()
    {
        return view('client.pages.account.dashboard');
    }

    /**
     * Display email edit form
     */
    public function editEmail()
    {
        return view('client.pages.account.edit._email');
    }

    /**
     * Update email
     */
    public function updateEmail(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email', 'unique:users,email,' . auth()->id()],
        ]);

        // Logic to send email verification

        return redirect()->route('customer.email.edit')->with('success', 'Un email de confirmation a été envoyé');
    }

    /**
     * Delete account
     */
    public function destroy(Request $request)
    {
        $request->validate([
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        // Logic to delete user account
        // auth()->logout();
        // $user->delete();

        return redirect()->route('public.home')->with('success', 'Compte supprimé avec succès');
    }
}
