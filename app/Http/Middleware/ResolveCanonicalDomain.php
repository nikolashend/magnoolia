<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Phase 35.0 — make the advertised canonical follow the host that actually serves
 * the site, with no configuration change.
 *
 * The deployment keeps two public hosts, one of which redirects to the other at
 * the hosting layer. Today `magnoolia.ee` 302s to `magnoolia.estlanda.ee`; that may
 * later be reversed. Because only the *serving* host ever reaches this application
 * (the other one is answered by Apache before PHP runs), the request host is by
 * definition the canonical one — so we adopt it.
 *
 * Consequence: flipping the redirect in the hosting panel is the whole migration.
 * Canonical, hreflang, OG, schema @id's, robots.txt and all 107 sitemap URLs move
 * with it. No `.env` edit, no deploy, no code change.
 *
 * Safety: only hosts listed in `magnoolia.seo.public_hosts` are adopted. Anything
 * else — localhost, an IP, a preview domain, or an injected `Host:` header — is
 * ignored and the configured `canonical_domain` is used instead, so this cannot be
 * used to poison the canonical/OG URLs of a cached page.
 */
class ResolveCanonicalDomain
{
    public function handle(Request $request, Closure $next): Response
    {
        $host = $this->normalise($request->getHost());

        $publicHosts = array_map(
            fn (string $h): string => $this->normalise($h),
            (array) config('magnoolia.seo.public_hosts', [])
        );

        if (in_array($host, $publicHosts, true)) {
            // Public hosts are always served over HTTPS, so the canonical is https
            // even when the request arrived on http.
            $base = 'https://' . $host;

            config([
                'magnoolia.canonical_domain'      => $base,
                'magnoolia.seo.canonical_base'    => $base,
                'magnoolia.seo.production_domain' => $base,
            ]);
        }

        return $next($request);
    }

    /** Lowercase and drop a leading "www." so www and apex share one canonical. */
    private function normalise(string $host): string
    {
        $host = strtolower(trim($host));

        return str_starts_with($host, 'www.') ? substr($host, 4) : $host;
    }
}
