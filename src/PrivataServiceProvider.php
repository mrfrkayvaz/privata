<?php

namespace Privata;

use Illuminate\Support\ServiceProvider;
use Privata\Services\PrivataManager;

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

        $this->app->singleton(PrivataManager::class, function () {
            return new PrivataManager();
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

        $this->registerObservers();
    }

    /**
     * Register model observers
     */
    protected function registerObservers(): void
    {
    }
}
