<?php

declare(strict_types=1);

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        apiPrefix: 'api/v1',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // Global middleware for all requests
        $middleware->append([
            App\Http\Middleware\SecurityHeaders::class,
            App\Http\Middleware\SanitizeInput::class,
        ]);

        // API middleware group
        $middleware->api(append: [
            App\Http\Middleware\Cors::class,
            App\Http\Middleware\ApiVersioning::class,
            App\Http\Middleware\ApiRateLimit::class,
        ]);

        // Rate limit aliases for different endpoints
        $middleware->alias([
            'rate.limit.api' => App\Http\Middleware\ApiRateLimit::class.':api',
            'rate.limit.auth.login' => App\Http\Middleware\ApiRateLimit::class.':auth:login',
            'rate.limit.auth.register' => App\Http\Middleware\ApiRateLimit::class.':auth:register',
            'rate.limit.auth.password_reset' => App\Http\Middleware\ApiRateLimit::class.':auth:password_reset',
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
