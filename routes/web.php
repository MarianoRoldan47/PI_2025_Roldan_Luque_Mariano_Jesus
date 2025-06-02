<?php

use App\Http\Controllers\Admin\UserApprovalController;
use App\Http\Controllers\ViewsControllers\AlertaStockController;
use App\Http\Controllers\ViewsControllers\AlmacenController;
use App\Http\Controllers\ViewsControllers\EstanteriasController;
use App\Http\Controllers\ViewsControllers\UserController;
use App\Http\Controllers\ViewsControllers\CategoriasController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ViewsControllers\DashboardController;
use App\Http\Controllers\ViewsControllers\MovimientosController;
use App\Http\Controllers\ViewsControllers\ProductosController;
use App\Http\Controllers\ViewsControllers\ZonasController;
use App\Http\Middleware\EnsureUserIsAdmin;
use App\Http\Middleware\EnsureUserIsApproved;

Route::middleware(['auth'])->group(function () {
    Route::middleware([EnsureUserIsApproved::class])->group(function () {
        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

        Route::get('/productos/pdf/inventario', [App\Http\Controllers\ViewsControllers\ProductosController::class, 'generarPdfInventario'])->name('productos.pdf.inventario');

        Route::middleware(EnsureUserIsAdmin::class)->group(function () {

            Route::get('/users/solicitudes', [UserController::class, 'solicitudes'])->name('users.solicitudes');
            Route::post('/users/{user}/aprobar', [UserController::class, 'aprobar'])->name('users.aprobar');
            Route::delete('/users/{user}/rechazar', [UserController::class, 'rechazar'])->name('users.rechazar');


            Route::get('/estanterias/create', [EstanteriasController::class, 'create'])->name('estanterias.create');
            Route::post('/estanterias', [EstanteriasController::class, 'store'])->name('estanterias.store');
            Route::get('/estanterias/{estanteria}/edit', [EstanteriasController::class, 'edit'])->name('estanterias.edit');
            Route::put('/estanterias/{estanteria}', [EstanteriasController::class, 'update'])->name('estanterias.update');
            Route::delete('/estanterias/{estanteria}', [EstanteriasController::class, 'destroy'])->name('estanterias.destroy');


            Route::get('/zonas/create', [ZonasController::class, 'create'])->name('zonas.create');
            Route::post('/zonas', [ZonasController::class, 'store'])->name('zonas.store');
            Route::get('/zonas/{zona}/edit', [ZonasController::class, 'edit'])->name('zonas.edit');
            Route::put('/zonas/{zona}', [ZonasController::class, 'update'])->name('zonas.update');
            Route::delete('/zonas/{zona}', [ZonasController::class, 'destroy'])->name('zonas.destroy');


            Route::delete('/alertas/{alerta}', [AlertaStockController::class, 'destroy'])->name('alertas.destroy');


            Route::delete('/productos/{producto}', [ProductosController::class, 'destroy'])->name('productos.destroy');


            Route::resource('users', UserController::class);
        });

        Route::resource('categorias', CategoriasController::class);

        Route::get('/movimientos/get-estanterias', [MovimientosController::class, 'getEstanterias'])
            ->name('movimientos.get-estanterias');

        Route::get('/movimientos', [MovimientosController::class, 'index'])->name('movimientos.index');
        Route::get('/movimientos/create', [MovimientosController::class, 'create'])->name('movimientos.create');
        Route::post('/movimientos', [MovimientosController::class, 'store'])->name('movimientos.store');
        Route::get('/movimientos/{movimiento}', [MovimientosController::class, 'show'])->name('movimientos.show');



        Route::get('/movimientos/{movimiento}/edit', [MovimientosController::class, 'edit'])
            ->middleware('can:update,movimiento')
            ->name('movimientos.edit');

        Route::put('/movimientos/{movimiento}', [MovimientosController::class, 'update'])
            ->middleware('can:update,movimiento')
            ->name('movimientos.update');


        Route::delete('/movimientos/{movimiento}', [MovimientosController::class, 'destroy'])
            ->middleware('can:delete,movimiento')
            ->name('movimientos.destroy');


        Route::get('/estanterias', [EstanteriasController::class, 'index'])->name('estanterias.index');
        Route::get('/estanterias/{estanteria}', [EstanteriasController::class, 'show'])->name('estanterias.show');

        Route::get('/zonas', [ZonasController::class, 'index'])->name('zonas.index');
        Route::get('/zonas/{zona}', [ZonasController::class, 'show'])->name('zonas.show');


        Route::get('/alertas', [AlertaStockController::class, 'index'])->name('alertas.index');
        Route::get('/alertas/{alerta}', [AlertaStockController::class, 'show'])->name('alertas.show');


        Route::resource('productos', ProductosController::class)->except(['destroy']);


        Route::get('/almacen', [AlmacenController::class, 'index'])->name('almacen.index');

        Route::view('perfil', 'profile')->name('perfil');
    });
});

require __DIR__ . '/auth.php';
