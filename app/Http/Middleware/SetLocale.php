<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

class SetLocale
{
    /**
     * Supported locales.
     */
    protected array $supportedLocales = ['en', 'et', 'ru'];

    public function handle(Request $request, Closure $next)
    {
        $locale = Session::get('locale', config('app.locale'));

        if (!in_array($locale, $this->supportedLocales)) {
            $locale = config('app.locale');
        }

        App::setLocale($locale);

        return $next($request);
    }
}
