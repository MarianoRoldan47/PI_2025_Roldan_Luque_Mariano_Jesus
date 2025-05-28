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
use App\Http\Middleware\EnsureUserIsApproved;

Route::middleware(['auth'])->group(function () {
    Route::middleware([EnsureUserIsApproved::class])->group(function () {
        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

        Route::get('/movimientos/get-estanterias', [MovimientosController::class, 'getEstanterias'])
            ->name('movimientos.get-estanterias');

        // Rutas personalizadas de users ANTES del resource
        Route::get('/users/solicitudes', [UserController::class, 'solicitudes'])->name('users.solicitudes');
        Route::post('/users/{user}/aprobar', [UserController::class, 'aprobar'])->name('users.aprobar');
        Route::delete('/users/{user}/rechazar', [UserController::class, 'rechazar'])->name('users.rechazar');



        // Rutas de estanterias
        Route::get('/estanterias/create', [EstanteriasController::class, 'create'])->name('estanterias.create');
        Route::post('/estanterias', [EstanteriasController::class, 'store'])->name('estanterias.store');
        Route::get('/estanterias/{estanteria}', [EstanteriasController::class, 'show'])->name('estanterias.show');
        Route::get('/estanterias/{estanteria}/edit', [EstanteriasController::class, 'edit'])->name('estanterias.edit');
        Route::put('/estanterias/{estanteria}', [EstanteriasController::class, 'update'])->name('estanterias.update');
        Route::delete('/estanterias/{estanteria}', [EstanteriasController::class, 'destroy'])->name('estanterias.destroy');


        // Rutas de zonas
        Route::get('/zonas/create', [ZonasController::class, 'create'])->name('zonas.create');
        Route::post('/zonas', [ZonasController::class, 'store'])->name('zonas.store');
        Route::get('/zonas/{zona}', [ZonasController::class, 'show'])->name('zonas.show');
        Route::get('/zonas/{zona}/edit', [ZonasController::class, 'edit'])->name('zonas.edit');
        Route::put('/zonas/{zona}', [ZonasController::class, 'update'])->name('zonas.update');
        Route::delete('/zonas/{zona}', [ZonasController::class, 'destroy'])->name('zonas.destroy');

        // Rutas de alertas de stock
        Route::get('/alertas', [AlertaStockController::class, 'index'])->name('alertas.index');
        Route::get('/alertas/{alerta}', [AlertaStockController::class, 'show'])->name('alertas.show');
        Route::delete('/alertas/{alerta}', [AlertaStockController::class, 'destroy'])->name('alertas.destroy');

        // Rutas de resources
        Route::get('/almacen', [AlmacenController::class, 'index'])->name('almacen.index');
        Route::resource('movimientos', MovimientosController::class);
        Route::resource('productos', ProductosController::class);
        Route::resource('categorias', CategoriasController::class);
        Route::resource('users', UserController::class);

        Route::view('perfil', 'profile')->name('perfil');
    });
});

require __DIR__ . '/auth.php';
