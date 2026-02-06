<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PolicyController extends Controller
{
    /**
     * Display privacy policy page
     */
    public function privacy()
    {
        return view('client.pages.policies.privacy');
    }

    /**
     * Display cookie policy page
     */
    public function cookie()
    {
        return view('client.pages.policies.cookie');
    }

    /**
     * Display terms and conditions page
     */
    public function terms()
    {
        return view('client.pages.policies.terms');
    }
}
