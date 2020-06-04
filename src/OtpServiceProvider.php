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
        // Optional methods to load your package assets
        if ($this->app->runningInConsole()) {
            $this->publishConfig();
            $this->publishMigrations();
        }
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        // Automatically apply the package configuration
        $this->mergeConfigFrom(__DIR__ . '/../config/config.php', 'otp');

        // Register the main class to use with the facade
        $this->app->singleton('otp', function () {
            return new Otp();
        });
    }

    protected function publishConfig()
    {
        $stub = __DIR__ . '/../config/config.php';

        $target = config_path('otp.php');

        $this->publishes([
            $stub => $target,
        ], 'otp.config');
    }

    protected function publishMigrations()
    {
        $timestamp = date('Y_m_d_His');

        $stub = __DIR__ . '/../database/migrations/otp_setup_table.php';

        $target = database_path('migrations/' . $timestamp . '_otp_setup_table.php');

        $this->publishes([
            $stub => $target,
        ], 'otp.migrations');
    }
}
