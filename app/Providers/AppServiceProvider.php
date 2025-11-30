<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\FirebaseService;
use Kreait\Firebase\Database;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register()
    {
        // Bind FirebaseService
        $this->app->singleton(FirebaseService::class, function ($app) {
            return new FirebaseService($app->make(Database::class));
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot()
    {
        //
    }
}
