<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ApiVersioning
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $version = $this->resolveApiVersion($request);
        $config = config('security.api_versioning', []);

        // Set the API version in the request
        $request->attributes->set('api_version', $version);

        // Check if version is supported
        $supportedVersions = $config['supported_versions'] ?? ['v1'];
        
        if (!in_array($version, $supportedVersions)) {
            return response()->json([
                'error' => 'Unsupported API version',
                'message' => "API version '{$version}' is not supported.",
                'supported_versions' => $supportedVersions,
            ], 400);
        }

        $response = $next($request);

        // Add API version header to response
        $response->headers->set('X-API-Version', $version);

        // Add deprecation warning if needed
        if ($config['deprecation_notice'] ?? false) {
            $response->headers->set(
                'X-API-Deprecation-Warning',
                'This API version will be deprecated. Please migrate to the latest version.'
            );
        }

        return $response;
    }

    /**
     * Resolve API version from request.
     */
    protected function resolveApiVersion(Request $request): string
    {
        // Check Accept header first (e.g., application/vnd.forkflash.v1+json)
        $acceptHeader = $request->header('Accept', '');
        if (preg_match('/application\/vnd\.forkflash\.(v\d+)\+json/', $acceptHeader, $matches)) {
            return $matches[1];
        }

        // Check X-API-Version header
        if ($version = $request->header('X-API-Version')) {
            return $version;
        }

        // Check URL path (e.g., /api/v1/...)
        $path = $request->path();
        if (preg_match('/^api\/(v\d+)\//', $path, $matches)) {
            return $matches[1];
        }

        // Check query parameter
        if ($version = $request->query('version')) {
            return $version;
        }

        // Return default version
        return config('security.api_versioning.default_version', 'v1');
    }
}
