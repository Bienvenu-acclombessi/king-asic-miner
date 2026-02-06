<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CompanyController extends Controller
{
    /**
     * Display about page
     */
    public function about()
    {
        return view('client.pages.company.about');
    }

    /**
     * Display blog page
     */
    public function blog()
    {
        return view('client.pages.company.blog');
    }

    /**
     * Display blog detail page
     */
    public function blogDetail($slug)
    {
        return view('client.pages.company.blog_detail', compact('slug'));
    }

    /**
     * Display contact page
     */
    public function contact()
    {
        return view('client.pages.company.contact');
    }

    /**
     * Display FAQ page
     */
    public function faq()
    {
        return view('client.pages.company.faq');
    }

    /**
     * Display fraud prevention page
     */
    public function fraudPrevention()
    {
        return view('client.pages.company.fraud-prevention');
    }

    /**
     * Display staff authentication page
     */
    public function staffAuthentification()
    {
        return view('client.pages.company.staff_authentification');
    }
}
