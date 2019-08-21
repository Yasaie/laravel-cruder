<?php

namespace Yasaie\Cruder;

/**
 * Class    ServiceProvider
 *
 * @author  Payam Yasaie <payam@yasaie.ir>
 * @since   2019-08-21
 *
 * @package Yasaie\Cruder
 */
class ServiceProvider extends \Illuminate\Support\ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->publishes([
            __DIR__.'/../resources/views' => resource_path('views/vendor/cruder'),
        ], 'views');
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'Cruder');
    }
}
