<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Phase 33.3 — gate "Advanced — ADME only" sections (audit log, advanced
 * translations/languages/navigation) to the full system admin. The client admin
 * role (magnoolia_client_admin) does daily work + publishing, but must NOT reach
 * these developer/technical sections.
 */
class EnsureMagnooliaSystemAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();
        if (!$user || $user->role !== 'magnoolia_admin') {
            abort(403);
        }

        return $next($request);
    }
}
