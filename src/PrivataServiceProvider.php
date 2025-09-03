<?php

namespace Privata;

use Illuminate\Support\ServiceProvider;

class PrivataServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->mergeConfigFrom(
            __DIR__.'/../config/privata.php', 'privata'
        );

        $this->app->singleton('privata', function () {
            return new PrivataManager(
                config('privata')
            );
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $this->publishes([
            __DIR__.'/../config/privata.php' => config_path('privata.php'),
        ], 'privata-config');

        $this->publishes([
            __DIR__.'/../database/migrations' => database_path('migrations'),
        ], 'privata-migrations');

        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');

        $this->registerObservers();
    }

    /**
     * Register model observers
     */
    protected function registerObservers(): void
    {
    }
}
