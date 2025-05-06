<?php

namespace App\Providers;

use App\Models\Estanteria;
use App\Models\Movimiento;
use App\Models\Producto;
use App\Observers\EstanteriaObserver;
use App\Observers\MovimientoObserver;
use App\Observers\ProductoObserver;
use Illuminate\Support\ServiceProvider;

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
        Producto::observe(ProductoObserver::class);
        Movimiento::observe(MovimientoObserver::class);
        Estanteria::observe(EstanteriaObserver::class);
    }
}
