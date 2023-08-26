<?php

namespace App\Providers;

use App\Services\MovieService;
use Illuminate\Support\ServiceProvider;

class MovieServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(MovieService::class, function ($app) {
            return new MovieService();  // Here, you can pass any dependencies if required.
        });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
