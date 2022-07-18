<?php

namespace Emotality\Everlytic;

use Illuminate\Support\ServiceProvider;

class EverlyticServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register()
    {
        $this->app->singleton(EverlyticAPI::class, function () {
            return new EverlyticAPI();
        });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/everlytic.php' => config_path('everlytic.php'),
            ], 'config');
        }

        \Mail::extend('everlytic', function () {
            return new EverlyticMailTransport();
        });
    }
}
