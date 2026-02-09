<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class HostingController extends Controller
{
    /**
     * Display hosting page
     */
    public function index()
    {
        return view('client.pages.hosting.index');
    }
}
