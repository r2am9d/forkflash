<?php

declare(strict_types=1);

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Route;

Route::get('/health', function () {
    $checkDatabase = function (): string {
        try {
            DB::connection()->getPdo();

            return 'connected';
        } catch (Exception) {
            return 'disconnected';
        }
    };

    $checkRedis = function (): string {
        try {
            Redis::ping();

            return 'connected';
        } catch (Exception) {
            return 'disconnected';
        }
    };

    return response()->json([
        'status' => 'healthy',
        'timestamp' => now()->toISOString(),
        'environment' => app()->environment(),
        'version' => config('app.version', '1.0.0'),
        'services' => [
            'database' => $checkDatabase(),
            'redis' => $checkRedis(),
        ],
    ]);
});
