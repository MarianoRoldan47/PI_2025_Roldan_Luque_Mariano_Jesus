<?php

namespace App\Providers;

use App\Models\Movimiento;
use App\Models\Producto;
use App\Observers\MovimientoObserver;
use App\Observers\ProductoObserver;
use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\URL;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Paginator::useBootstrap();
        Producto::observe(ProductoObserver::class);
        Movimiento::observe(MovimientoObserver::class);

        (new CustomValidationServiceProvider($this->app))->boot();

        if (env('APP_ENV') === 'production') {
            URL::forceScheme('https');
        }
    }
}
