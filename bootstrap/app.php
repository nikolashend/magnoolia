<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'magnoolia.admin' => \App\Http\Middleware\EnsureMagnooliaAdmin::class,
            'magnoolia.publish-admin' => \App\Http\Middleware\EnsureMagnooliaPublishAdmin::class,
            'magnoolia.system-admin' => \App\Http\Middleware\EnsureMagnooliaSystemAdmin::class,
            'magnoolia.login-throttle' => \App\Http\Middleware\MagnooliaLoginThrottle::class,
        ]);

        $middleware->web(append: [
            \App\Http\Middleware\SetLocale::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
