<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index()
    {
        return view('admin.pages.orders.index');
    }

    public function show($id)
    {
        return view('admin.pages.orders.show');
    }

    public function edit($id)
    {
        return view('admin.pages.orders.edit');
    }

    public function update(Request $request, $id)
    {
        // Logic will be added later
        return redirect()->route('admin.orders.index');
    }
}
