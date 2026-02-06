<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class HelpCenterController extends Controller
{
    /**
     * Display help center home page
     */
    public function index()
    {
        return view('client.pages.help-center.index');
    }

    /**
     * Display help article
     */
    public function article($slug)
    {
        return view('client.pages.help-center.article', compact('slug'));
    }

    /**
     * Display help category
     */
    public function category($slug)
    {
        return view('client.pages.help-center.category', compact('slug'));
    }

    /**
     * Display search results
     */
    public function search(Request $request)
    {
        $query = $request->get('q', '');
        return view('client.pages.help-center.search', compact('query'));
    }
}
