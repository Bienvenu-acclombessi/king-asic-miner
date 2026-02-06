<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CheckoutController extends Controller
{
    /**
     * Display checkout page
     */
    public function index()
    {
        return view('client.pages.checkout.index');
    }
}
