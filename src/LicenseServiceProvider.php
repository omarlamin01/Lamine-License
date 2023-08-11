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
        $this->loadMigrationsFrom(__DIR__.'/Migrations');

        $this->loadViewsFrom(__DIR__.'/Views', 'Lamine/License');

        $this->publishes([
            __DIR__.'/Config/license.php' => config_path('license.php'),
        ], 'config');

        $this->publishes([
            __DIR__.'/Views' => resource_path('views/vendor/Lamine/License'),
        ], 'views');
    }

    /**
     * Register the application services.
     */
    public function register()
    {

    }
}