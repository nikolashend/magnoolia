<?php

namespace App\Http\Controllers;

class HomeController extends Controller
{
    public function index(string $locale = 'et')
    {
        // Validate locale is supported; fall back to 'et'
        $supported = ['et', 'ru', 'en'];
        if (! in_array($locale, $supported)) {
            $locale = 'et';
        }
        app()->setLocale($locale);

        return view('pages.home');
    }
}
