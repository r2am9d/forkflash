<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;

Route::get('/health', function () {
    return response()->json([
        'status' => 'healthy',
        'timestamp' => now()->toISOString(),
        'environment' => app()->environment(),
        'version' => config('app.version', '1.0.0'),
        'services' => [
            'database' => checkDatabaseConnection(),
            'redis' => checkRedisConnection(),
        ]
    ]);
});

function checkDatabaseConnection() {
    try {
        DB::connection()->getPdo();
        return 'connected';
    } catch (Exception $e) {
        return 'disconnected';
    }
}

function checkRedisConnection() {
    try {
        Redis::ping();
        return 'connected';
    } catch (Exception $e) {
        return 'disconnected';
    }
}
