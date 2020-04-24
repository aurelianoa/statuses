<?php

namespace Statch\Statuses;

use Illuminate\Support\ServiceProvider;
use Statch\Statuses\Contracts\Status as StatusContract;

class StatusesServiceProvider extends ServiceProvider
{
    /**
     * Perform post-registration booting of services.
     */
    public function boot()
    {
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');

        // Publishing is only necessary when using the CLI.
        if ($this->app->runningInConsole()) {
            $this->bootForConsole();
        }

        $this->app->bind(StatusContract::class, config('statch-statuses.models.status'));
    }

    /**
     * Register any package services.
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/statch-statuses.php', 'statch-statuses');
    }

    /**
     * Console-specific booting.
     */
    protected function bootForConsole()
    {
        // Publishing the configuration file.
        $this->publishes([
            __DIR__.'/../config/statch-statuses.php' => config_path('statch-statuses.php'),
        ], 'config');

        // Publishing the migration file.
        $this->publishes([
            __DIR__.'/../database/migrations/' => database_path('migrations'),
        ], 'migrations');
    }
}
