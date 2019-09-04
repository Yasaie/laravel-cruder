<?php

namespace Yasaie\Cruder;

use Illuminate\Auth\Middleware\Authenticate;
use Route;

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
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {

        $router = $this->app['router'];

        $router->name('crud.media.')
            ->prefix('crud')
            ->namespace('Yasaie\Cruder')
            ->middleware(['web', 'auth'])
            ->group(function () {
                Route::post('media/upload', 'MediaController@upload')
                    ->name('upload');
                Route::delete('media/unlink/{id?}', 'MediaController@unlink')
                    ->name('unlink');
            });

        # Loads
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'Cruder');
        $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'Cruder');

        # Publishes
        $this->publishes([
            __DIR__.'/../resources/views' => resource_path('views/vendor/cruder'),
        ], 'views');

        $this->publishes([
            __DIR__.'/../public' => public_path('vendor/cruder'),
        ], 'public');

        $this->publishes([
            __DIR__.'/../resources/lang' => resource_path('lang/vendor/cruder'),
        ], 'lang');
    }
}
