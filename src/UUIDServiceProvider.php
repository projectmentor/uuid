<?php

namespace projectmentor\UUID;

use Illuminate\Support\ServiceProvider;

class UUIDServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {

    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(UUID::class, function () {
            return new UUID();
        });

        $this->app->alias(UUID::class, 'uuid');
    }
}
