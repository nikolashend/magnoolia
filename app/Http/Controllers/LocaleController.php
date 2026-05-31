<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class LocaleController extends Controller
{
    protected array $supportedLocales = ['en', 'et', 'ru'];

    public function switch(Request $request, string $locale)
    {
        if (!in_array($locale, $this->supportedLocales)) {
            $locale = 'et';
        }

        Session::put('locale', $locale);

        // Build locale-prefixed version of the referring URL
        $referer = $request->headers->get('referer', '/');
        $refPath = parse_url($referer, PHP_URL_PATH) ?? '/';

        // Strip existing locale prefix from referer path
        $cleanPath = preg_replace('#^/(ru|en)(/|$)#', '/', $refPath);
        $cleanPath = '/' . ltrim($cleanPath, '/');

        if ($locale === 'et') {
            return redirect($cleanPath);
        }

        return redirect('/' . $locale . $cleanPath);
    }
}
