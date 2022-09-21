<?php

namespace Creagia\LaravelSignPad;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class LarvelSignPadServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->make('Creagia\LaravelSignPad\app\Http\Controllers\LaravelSignPadController');
        $this->loadViewsFrom(__DIR__ . '/resources/views', 'sign');
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        include __DIR__ . '/routes/web.php';
        Blade::component('signature-pad', SignaturePad::class);
        $this->publishes(
            [
                __DIR__ . '/resources/assets/js/' => resource_path('js/creagia/'),
                __DIR__ . '/config/' => config_path(),
                __DIR__ . '/database/migrations/' => database_path('migrations/'),
            ],
            'sign-pad'
        );
    }
}
