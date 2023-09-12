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
        $this->loadViewsFrom(__DIR__.'/Views', 'Lamine/License');

        // Load Routes
        $this->loadRoutesFrom(__DIR__ . '/Routes/index.php');

        $this->publishes([
            __DIR__.'/Migrations' => database_path('migrations'),
        ], 'migrations');

        // publish routes
        $this->publishes([
            __DIR__.'/Routes/index.php' => base_path('routes/license.php'),
        ], 'routes');
    }

    /**
     * Register the application services.
     */
    public function register()
    {

    }
}