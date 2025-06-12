<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Cache\RateLimiter;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class ApiRateLimit
{
    public function __construct(
        protected RateLimiter $limiter
    ) {}

    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, string $limit = 'api'): ResponseAlias
    {
        $key = $this->resolveRequestSignature($request);
        $config = $this->getLimitConfig($limit);

        if ($this->limiter->tooManyAttempts($key, $config['attempts'])) {
            return $this->buildResponse($key, $config['attempts']);
        }

        $this->limiter->hit($key, $config['decay_seconds']);

        $response = $next($request);

        return $this->addHeaders($response, $config['attempts'], $key);
    }

    /**
     * Resolve request signature for rate limiting.
     */
    protected function resolveRequestSignature(Request $request): string
    {
        if ($user = $request->user()) {
            return 'api:user:' . $user->id;
        }

        return 'api:ip:' . $request->ip();
    }

    /**
     * Get rate limit configuration.
     */
    protected function getLimitConfig(string $limit): array
    {
        $configs = [
            'api' => config('security.api', ['attempts' => 60, 'decay_seconds' => 60]),
            'auth:login' => config('security.auth.login', ['attempts' => 5, 'decay_seconds' => 300]),
            'auth:register' => config('security.auth.registration', ['attempts' => 3, 'decay_seconds' => 3600]),
            'auth:password_reset' => config('security.auth.password_reset', ['attempts' => 3, 'decay_seconds' => 3600]),
        ];

        return $configs[$limit] ?? $configs['api'];
    }
    
    /**
     * Build rate limit exceeded response.
     */
    protected function buildResponse(string $key, int $maxAttempts): JsonResponse
    {
        $retryAfter = $this->limiter->availableIn($key);

        return response()->json([
            'error' => 'Rate limit exceeded',
            'message' => 'Too many requests. Please try again later.',
            'retry_after' => $retryAfter,
        ], 429, [
            'Retry-After' => $retryAfter,
            'X-RateLimit-Limit' => $maxAttempts,
            'X-RateLimit-Remaining' => 0,
        ]);
    }

    /**
     * Add rate limit headers to response.
     */
    protected function addHeaders(ResponseAlias $response, int $maxAttempts, string $key): ResponseAlias
    {
        $remaining = $this->limiter->remaining($key, $maxAttempts);
        $retryAfter = $this->limiter->availableIn($key);

        $response->headers->add([
            'X-RateLimit-Limit' => $maxAttempts,
            'X-RateLimit-Remaining' => max(0, $remaining),
        ]);

        if ($remaining === 0) {
            $response->headers->set('Retry-After', (string) $retryAfter);
        }

        return $response;
    }
}
