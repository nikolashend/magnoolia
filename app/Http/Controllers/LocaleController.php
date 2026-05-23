<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class LocaleController extends Controller
{
    protected array $supportedLocales = ['en', 'et', 'ru'];

    public function switch(Request $request, string $locale)
    {
        if (in_array($locale, $this->supportedLocales)) {
            Session::put('locale', $locale);
        }

        return redirect()->back()->withInput();
    }
}
