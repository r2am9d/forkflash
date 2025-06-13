<?php

declare(strict_types=1);

use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\RecipeController;
use App\Http\Controllers\Api\V1\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes v1
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group.
|
*/

// Health check endpoint (no authentication required)
Route::get('/health', fn () => response()->json([
    'status' => 'healthy',
    'timestamp' => now()->toISOString(),
    'version' => config('app.version', '1.0.0'),
]));

// Authentication routes with enhanced rate limiting
Route::prefix('auth')->group(function (): void {
    // Public authentication endpoints
    Route::middleware(['rate.limit.auth.login'])->group(function (): void {
        Route::post('/login', [AuthController::class, 'login']);
        Route::post('/refresh', [AuthController::class, 'refresh']);
    });

    Route::middleware(['rate.limit.auth.register'])->group(function (): void {
        Route::post('/register', [AuthController::class, 'register']);
    });

    Route::middleware(['rate.limit.auth.password_reset'])->group(function (): void {
        Route::post('/forgot-password', [AuthController::class, 'forgotPassword']);
        Route::post('/reset-password', [AuthController::class, 'resetPassword']);
    });

    // Protected authentication endpoints
    Route::middleware(['auth:sanctum'])->group(function (): void {
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::get('/me', [AuthController::class, 'me']);
        Route::put('/profile', [AuthController::class, 'updateProfile']);
        Route::put('/password', [AuthController::class, 'updatePassword']);
    });
});

// Protected API routes
Route::middleware(['auth:sanctum'])->group(function (): void {

    // User management
    // Route::prefix('users')->group(function () {
    //     Route::get('/profile', [UserController::class, 'profile']);
    //     Route::put('/profile', [UserController::class, 'updateProfile']);
    //     Route::delete('/account', [UserController::class, 'deleteAccount']);
    // });

    // Recipe management
    // Route::apiResource('recipes', RecipeController::class);
    // Route::prefix('recipes')->group(function () {
    //     Route::post('/{recipe}/favorite', [RecipeController::class, 'favorite']);
    //     Route::delete('/{recipe}/favorite', [RecipeController::class, 'unfavorite']);
    //     Route::get('/favorites', [RecipeController::class, 'favorites']);
    //     Route::post('/{recipe}/rate', [RecipeController::class, 'rate']);
    // });

    // Future endpoints (placeholders)
    Route::prefix('grocery-lists')->group(function (): void {
        // Route::apiResource('/', GroceryListController::class);
    });

    Route::prefix('meal-plans')->group(function (): void {
        // Route::apiResource('/', MealPlanController::class);
    });

    Route::prefix('voice-assistant')->group(function (): void {
        // Route::post('/query', [VoiceAssistantController::class, 'query']);
    });
});

// Public recipe browsing (with rate limiting)
Route::middleware(['rate.limit.api'])->group(function (): void {
    // Route::get('/recipes/public', [RecipeController::class, 'publicIndex']);
    // Route::get('/recipes/{recipe}/public', [RecipeController::class, 'publicShow']);
    // Route::get('/categories', [RecipeController::class, 'categories']);
    // Route::get('/search', [RecipeController::class, 'search']);
});
