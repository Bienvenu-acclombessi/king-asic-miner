<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PromotionController extends Controller
{
    public function index()
    {
        return view('admin.pages.promotions.index');
    }

    public function create()
    {
        return view('admin.pages.promotions.create');
    }

    public function store(Request $request)
    {
        // Logic will be added later
        return redirect()->route('admin.promotions.index');
    }

    public function show($id)
    {
        return view('admin.pages.promotions.show');
    }

    public function edit($id)
    {
        return view('admin.pages.promotions.edit');
    }

    public function update(Request $request, $id)
    {
        // Logic will be added later
        return redirect()->route('admin.promotions.index');
    }

    public function destroy($id)
    {
        // Logic will be added later
        return redirect()->route('admin.promotions.index');
    }
}
