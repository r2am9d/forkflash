<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

final class Cors
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Handle preflight requests
        if ($request->isMethod('OPTIONS')) {
            return $this->handlePreflightRequest($request);
        }

        $response = $next($request);

        return $this->addCorsHeaders($request, $response);
    }

    /**
     * Handle preflight OPTIONS request.
     */
    private function handlePreflightRequest(Request $request): Response
    {
        $response = response('', 200);

        return $this->addCorsHeaders($request, $response);
    }

    /**
     * Add CORS headers to response.
     */
    private function addCorsHeaders(Request $request, Response $response): Response
    {
        $config = config('security.cors', []);

        $origin = $request->header('Origin');
        $allowedOrigins = $config['allowed_origins'] ?? ['*'];

        // Check if origin is allowed
        if (in_array('*', $allowedOrigins) || in_array($origin, $allowedOrigins)) {
            $response->headers->set('Access-Control-Allow-Origin', $origin ?: '*');
        }

        $response->headers->set(
            'Access-Control-Allow-Methods',
            implode(', ', $config['allowed_methods'] ?? ['GET', 'POST', 'PUT', 'DELETE'])
        );

        $response->headers->set(
            'Access-Control-Allow-Headers',
            implode(', ', $config['allowed_headers'] ?? ['*'])
        );

        if (! empty($config['exposed_headers'])) {
            $response->headers->set(
                'Access-Control-Expose-Headers',
                implode(', ', $config['exposed_headers'])
            );
        }

        $response->headers->set(
            'Access-Control-Max-Age',
            (string) ($config['max_age'] ?? 86400)
        );

        if ($config['supports_credentials'] ?? false) {
            $response->headers->set('Access-Control-Allow-Credentials', 'true');
        }

        return $response;
    }
}
