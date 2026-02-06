<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display product detail page
     */
    public function show($slug)
    {
        return view('client.pages.product.index', compact('slug'));
    }
}
