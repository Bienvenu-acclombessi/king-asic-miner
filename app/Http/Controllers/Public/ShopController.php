<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ShopController extends Controller
{
    /**
     * Display shop page
     */
    public function index()
    {
        return view('client.pages.shop.index');
    }

    /**
     * Display category view
     */
    public function categoryView($slug)
    {
        return view('client.pages.shop.category_view', compact('slug'));
    }
}
