<?php

namespace Ivfuture\EventNotification;

use Illuminate\Support\ServiceProvider;

class NotificationServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishMigrations();
    }

    private function publishMigrations()
    {
        $path = $this->getMigrationsPath();

        // Copy the content of migrations folder from the package to the migrations folder from project
        $this->publishes([$path => database_path('migrations')], 'migrations');
    }

    private function getMigrationsPath()
    {
        return __DIR__ . '/../database/migrations/';
    }
}
