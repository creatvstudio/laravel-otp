<?php

namespace CreatvStudio\Otp;

use Illuminate\Support\ServiceProvider;

class OtpServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        /*
         * Optional methods to load your package assets
         */
        if ($this->app->runningInConsole()) {
            $this->loadMigrationsFrom(__DIR__.'/../database/migrations');

            $this->publishes([
                __DIR__.'/../database/migrations' => database_path('migrations'),
            ], 'otp-migrations');

            $this->publishes([
                __DIR__.'/../config/otp.php' => config_path('otp.php'),
            ], 'otp-config');
        }
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        // Automatically apply the package configuration
        $this->mergeConfigFrom(__DIR__.'/../config/config.php', 'otp');

        // Register the main class to use with the facade
        $this->app->singleton('otp', function () {
            return new Otp;
        });
    }
}
