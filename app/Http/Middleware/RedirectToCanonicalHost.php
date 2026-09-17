<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * The site must answer on one host only. The "www" twin of the configured
 * host (or the bare twin, when the site is configured with www) is sent to
 * the configured one so search engines never index two copies.
 *
 * Any other host (localhost, an IP, a staging domain) is left alone.
 */
class RedirectToCanonicalHost
{
    public function handle(Request $request, Closure $next): Response
    {
        $siteUrl = rtrim((string) config('app.url'), '/');
        $canonicalHost = parse_url($siteUrl, PHP_URL_HOST);

        if (is_string($canonicalHost) && $this->isTwin($request->getHost(), $canonicalHost)) {
            $path = trim($request->getPathInfo(), '/');
            $query = $request->getQueryString();

            return redirect()->to($siteUrl.($path !== '' ? '/'.$path : '').($query ? '?'.$query : ''), 301);
        }

        return $next($request);
    }

    private function isTwin(string $host, string $canonicalHost): bool
    {
        return $host === 'www.'.$canonicalHost || 'www.'.$host === $canonicalHost;
    }
}
