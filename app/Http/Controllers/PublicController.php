<?php

namespace App\Http\Controllers;

class PublicController extends Controller
{
    public function home()
    {
        return view('welcome');
    }

    public function features()
    {
        return view('public.features');
    }

    public function pricing()
    {
        return view('public.pricing');
    }

    public function documentation()
    {
        return view('public.documentation');
    }

    public function tutorials()
    {
        return view('public.tutorials');
    }
}
