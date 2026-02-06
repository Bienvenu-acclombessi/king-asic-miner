<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class EmailCampaignController extends Controller
{
    public function index()
    {
        return view('admin.pages.email-campaigns.index');
    }

    public function create()
    {
        return view('admin.pages.email-campaigns.create');
    }

    public function store(Request $request)
    {
        // Logic will be added later
        return redirect()->route('admin.email-campaigns.index');
    }

    public function edit($id)
    {
        return view('admin.pages.email-campaigns.edit');
    }

    public function update(Request $request, $id)
    {
        // Logic will be added later
        return redirect()->route('admin.email-campaigns.index');
    }

    public function destroy($id)
    {
        // Logic will be added later
        return redirect()->route('admin.email-campaigns.index');
    }
}
