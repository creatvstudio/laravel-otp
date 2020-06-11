<?php

namespace CreatvStudio\Otp;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class OtpServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        Route::mixin(new OtpRouteMethods());

        // Optional methods to load your package assets
        if ($this->app->runningInConsole()) {
            $this->publish();
            $this->publishConfig();
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

    public function loadRoutes()
    {
        $this->loadRoutesFrom(__DIR__ . '/routes.php');
    }

    protected function publishConfig()
    {
        $stub = __DIR__ . '/../config/config.php';

        $target = config_path('otp.php');

        $this->publishes([
            $stub => $target,
        ], 'otp.config');
    }

    protected function publish()
    {
        $timestamp = date('Y_m_d_His');
        $this->publishes([
            __DIR__ . '/../database/migrations/otp_setup_table.php' => database_path('migrations/' . $timestamp . '_otp_setup_table.php'),
            __DIR__ . '/Http/Controllers' => app_path('Http/Controllers/Otp'),
            __DIR__ . '/resources/views' => resource_path('views/otp'),

        ], 'otp');
    }
}
