<?php

namespace Emotality\Everlytic;

use Illuminate\Support\ServiceProvider;

class EverlyticServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        \Mail::extend('everlytic', function (array $config) {
            return new EverlyticMailTransport($config);
        });
    }

    /**
     * {@inheritDoc}
     */
    public function register()
    {
        //
    }
}
