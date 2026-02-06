<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index()
    {
        return view('admin.pages.customers.index');
    }

    public function create()
    {
        return view('admin.pages.customers.create');
    }

    public function store(Request $request)
    {
        // Logic will be added later
        return redirect()->route('admin.customers.index');
    }

    public function show($id)
    {
        return view('admin.pages.customers.show');
    }

    public function edit($id)
    {
        return view('admin.pages.customers.edit');
    }

    public function update(Request $request, $id)
    {
        // Logic will be added later
        return redirect()->route('admin.customers.index');
    }

    public function destroy($id)
    {
        // Logic will be added later
        return redirect()->route('admin.customers.index');
    }
}
