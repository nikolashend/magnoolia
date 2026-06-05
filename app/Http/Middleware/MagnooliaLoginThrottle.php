<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Symfony\Component\HttpFoundation\Response;

class MagnooliaLoginThrottle
{
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->is('admin/login') && $request->isMethod('post')) {
            $email = (string) $request->input('email', '');
            $key = 'magnoolia-admin-login|' . $request->ip() . '|' . mb_strtolower($email);

            if (RateLimiter::tooManyAttempts($key, 5)) {
                abort(429, 'Too many login attempts. Please try again later.');
            }

            RateLimiter::hit($key, 60);
        }

        return $next($request);
    }
}
