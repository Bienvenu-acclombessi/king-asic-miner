<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class BulkOrderController extends Controller
{
    /**
     * Display bulk order page
     */
    public function index()
    {
        return view('client.pages.bulk-order.index');
    }
}
