<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Baseline response headers for every page, the admin panel included.
 *
 * A Content-Security-Policy is deliberately not set here: the site relies on
 * inline scripts (theme bootstrap, Livewire, Filament), so a policy has to be
 * designed and tested on its own rather than added blindly.
 */
class SecurityHeaders
{
    /** @var array<string, string> */
    private const array HEADERS = [
        'X-Content-Type-Options' => 'nosniff',
        'X-Frame-Options' => 'SAMEORIGIN',
        'Referrer-Policy' => 'strict-origin-when-cross-origin',
        'Permissions-Policy' => 'camera=(), microphone=(), geolocation=(), payment=()',
    ];

    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        foreach (self::HEADERS as $name => $value) {
            $response->headers->set($name, $value);
        }

        // Browsers remember HSTS for a year, so only send it over a connection that is already secure.
        if ($request->isSecure()) {
            $response->headers->set('Strict-Transport-Security', 'max-age=31536000; includeSubDomains');
        }

        // Do not advertise the PHP version.
        if (function_exists('header_remove') && ! headers_sent()) {
            header_remove('X-Powered-By');
        }

        return $response;
    }
}
