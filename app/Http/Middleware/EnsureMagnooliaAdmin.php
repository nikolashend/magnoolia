<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureMagnooliaAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();
        // Control-center daily access: full admin, editor, and the client admin role.
        if (!$user || !in_array($user->role, ['magnoolia_admin', 'magnoolia_editor', 'magnoolia_client_admin'], true)) {
            abort(403);
        }

        return $next($request);
    }
}
