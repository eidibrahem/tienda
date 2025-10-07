<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ForceHttps
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Force HTTPS only in production, not in local development
        // Note: TrustProxies middleware handles X-Forwarded-Proto detection
        if (env('APP_ENV') === 'production' && env('FORCE_HTTPS', true)) {
            // Don't redirect if already secure or if it's a health check
            if (!$request->secure() && !$request->is('health') && !$request->is('up')) {
                return redirect()->secure($request->getRequestUri());
            }
        }

        $response = $next($request);

        // Remove HSTS header in local development to prevent browser caching HTTPS redirect
        if (env('APP_ENV') !== 'production') {
            $response->headers->remove('Strict-Transport-Security');
        }

        return $response;
    }
}

