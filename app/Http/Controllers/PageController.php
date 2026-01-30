<?php

namespace App\Http\Controllers;

class PageController extends Controller
{
    public function about()
    {
        return view('pages.about');
    }

    public function apiDocs()
    {
        return view('pages.api-docs');
    }
}
