<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

final class SanitizeInput
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (config('security.validation.sanitize_input', true)) {
            $this->sanitizeRequestData($request);
        }

        return $next($request);
    }

    /**
     * Sanitize request data.
     */
    private function sanitizeRequestData(Request $request): void
    {
        // Sanitize all input data
        $request->merge($this->sanitizeArray($request->all()));

        // Sanitize JSON data if present
        if ($request->isJson()) {
            $json = $request->json()->all();
            $request->json()->replace($this->sanitizeArray($json));
        }
    }

    /**
     * Recursively sanitize array data.
     *
     * @param  array<mixed>  $data
     * @return array<mixed>
     */
    private function sanitizeArray(array $data): array
    {
        $sanitized = [];

        foreach ($data as $key => $value) {
            $sanitizedKey = $this->sanitizeString((string) $key);

            if (is_array($value)) {
                $sanitized[$sanitizedKey] = $this->sanitizeArray($value);
            } elseif (is_string($value)) {
                $sanitized[$sanitizedKey] = $this->sanitizeString($value);
            } else {
                $sanitized[$sanitizedKey] = $value;
            }
        }

        return $sanitized;
    }

    /**
     * Sanitize string input.
     */
    private function sanitizeString(string $input): string
    {
        // Remove null bytes
        $input = str_replace(chr(0), '', $input);

        // Remove control characters except tabs, newlines, and carriage returns
        $input = preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]/', '', $input);

        // Trim whitespace
        $input = mb_trim((string) $input);

        // Remove potential XSS vectors
        $input = strip_tags($input);

        // Remove potential SQL injection vectors
        $input = str_replace([
            'UNION', 'SELECT', 'INSERT', 'UPDATE', 'DELETE', 'DROP', 'CREATE', 'ALTER',
            'EXEC', 'EXECUTE', 'SCRIPT', 'JAVASCRIPT', 'VBSCRIPT', 'ONLOAD', 'ONERROR',
        ], '', mb_strtoupper($input));

        return $input;
    }
}
