<?php

namespace Lamine\License;

use Illuminate\Support\ServiceProvider;

class LicenseServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        // Load migrations
        $this->loadMigrationsFrom(__DIR__.'/Migrations');

        // Load views
        $this->loadViewsFrom(__DIR__.'/Views/', 'License');

        // Load Routes
        $this->loadRoutesFrom(__DIR__ . '/Routes/index.php');

        $this->publishes([
            __DIR__.'/Migrations' => database_path('migrations'),
        ], 'migrations');

        // publish routes
        $this->publishes([
            __DIR__.'/Routes/index.php' => base_path('routes/license.php'),
        ], 'routes');

        // publish views
        $this->publishes([
            __DIR__.'/Views' => resource_path('views'),
        ], 'views');
    }

    /**
     * Register the application services.
     */
    public function register()
    {

    }
}