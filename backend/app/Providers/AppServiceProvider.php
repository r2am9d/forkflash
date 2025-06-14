<?php

declare(strict_types=1);

namespace App\Providers;

use App\Models\GroceryItem;
use App\Observers\GroceryItemObserver;
use Illuminate\Support\ServiceProvider;

final class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Register model observers
        GroceryItem::observe(GroceryItemObserver::class);
    }
}
