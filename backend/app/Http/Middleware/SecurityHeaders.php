<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

final class SecurityHeaders
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Get security configuration
        $config = config('security.security_headers', []);

        // Add HSTS header
        if ($config['hsts']['enabled'] ?? true) {
            $hstsValue = 'max-age='.($config['hsts']['max_age'] ?? 31536000);

            if ($config['hsts']['include_subdomains'] ?? true) {
                $hstsValue .= '; includeSubDomains';
            }

            if ($config['hsts']['preload'] ?? true) {
                $hstsValue .= '; preload';
            }

            $response->headers->set('Strict-Transport-Security', $hstsValue);
        }

        // Add CSP header
        if ($config['csp']['enabled'] ?? true) {
            $cspDirectives = [
                "default-src 'self'",
                "script-src 'self' 'unsafe-inline' 'unsafe-eval'",
                "style-src 'self' 'unsafe-inline'",
                "img-src 'self' data: https:",
                "font-src 'self'",
                "connect-src 'self'",
                "media-src 'self'",
                "object-src 'none'",
                "child-src 'self'",
                "frame-ancestors 'none'",
                "form-action 'self'",
                "base-uri 'self'",
            ];

            $cspHeader = ($config['csp']['report_only'] ?? false)
                ? 'Content-Security-Policy-Report-Only'
                : 'Content-Security-Policy';

            $response->headers->set($cspHeader, implode('; ', $cspDirectives));
        }

        // Add other security headers
        $response->headers->set(
            'Referrer-Policy',
            $config['referrer_policy'] ?? 'strict-origin-when-cross-origin'
        );

        $response->headers->set(
            'X-Frame-Options',
            $config['x_frame_options'] ?? 'DENY'
        );

        $response->headers->set(
            'X-Content-Type-Options',
            $config['x_content_type_options'] ?? 'nosniff'
        );

        $response->headers->set('X-XSS-Protection', '1; mode=block');
        $response->headers->set('Permissions-Policy', 'geolocation=(), microphone=(), camera=()');

        return $response;
    }
}
