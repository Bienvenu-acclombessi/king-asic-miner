<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CouponController extends Controller
{
    public function index()
    {
        return view('admin.pages.coupons.index');
    }

    public function create()
    {
        return view('admin.pages.coupons.create');
    }

    public function store(Request $request)
    {
        // Logic will be added later
        return redirect()->route('admin.coupons.index');
    }

    public function edit($id)
    {
        return view('admin.pages.coupons.edit');
    }

    public function update(Request $request, $id)
    {
        // Logic will be added later
        return redirect()->route('admin.coupons.index');
    }

    public function destroy($id)
    {
        // Logic will be added later
        return redirect()->route('admin.coupons.index');
    }
}
