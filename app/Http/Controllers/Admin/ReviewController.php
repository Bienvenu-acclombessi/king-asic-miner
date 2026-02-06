<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function index()
    {
        return view('admin.pages.reviews.index');
    }

    public function edit($id)
    {
        return view('admin.pages.reviews.edit');
    }

    public function update(Request $request, $id)
    {
        // Logic will be added later
        return redirect()->route('admin.reviews.index');
    }

    public function destroy($id)
    {
        // Logic will be added later
        return redirect()->route('admin.reviews.index');
    }
}
