<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class SetLocale
{
    /**
     * Supported locales.
     */
    protected array $supportedLocales = ['en', 'et', 'ru'];

    public function handle(Request $request, Closure $next)
    {
        // Locale is determined ONLY from the URL prefix.
        // /ru/... → ru, /en/... → en, anything else → et (default)
        $segment = $request->segment(1); // 'ru', 'en', or first path slug
        $locale  = in_array($segment, ['ru', 'en']) ? $segment : 'et';

        App::setLocale($locale);

        return $next($request);
    }
}
