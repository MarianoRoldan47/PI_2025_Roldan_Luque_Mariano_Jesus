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

        // Middleware para verificar si el usuario es administrador
        Route::middleware(EnsureUserIsAdmin::class)->group(function () {
            // Rutas personalizadas de users (solicitudes)
            Route::get('/users/solicitudes', [UserController::class, 'solicitudes'])->name('users.solicitudes');
            Route::post('/users/{user}/aprobar', [UserController::class, 'aprobar'])->name('users.aprobar');
            Route::delete('/users/{user}/rechazar', [UserController::class, 'rechazar'])->name('users.rechazar');

            // Rutas de estanterías - solo admin
            Route::get('/estanterias/create', [EstanteriasController::class, 'create'])->name('estanterias.create');
            Route::post('/estanterias', [EstanteriasController::class, 'store'])->name('estanterias.store');
            Route::get('/estanterias/{estanteria}/edit', [EstanteriasController::class, 'edit'])->name('estanterias.edit');
            Route::put('/estanterias/{estanteria}', [EstanteriasController::class, 'update'])->name('estanterias.update');
            Route::delete('/estanterias/{estanteria}', [EstanteriasController::class, 'destroy'])->name('estanterias.destroy');

            // Rutas de zonas - solo admin
            Route::get('/zonas/create', [ZonasController::class, 'create'])->name('zonas.create');
            Route::post('/zonas', [ZonasController::class, 'store'])->name('zonas.store');
            Route::get('/zonas/{zona}/edit', [ZonasController::class, 'edit'])->name('zonas.edit');
            Route::put('/zonas/{zona}', [ZonasController::class, 'update'])->name('zonas.update');
            Route::delete('/zonas/{zona}', [ZonasController::class, 'destroy'])->name('zonas.destroy');

            // Borrar alertas - solo admin
            Route::delete('/alertas/{alerta}', [AlertaStockController::class, 'destroy'])->name('alertas.destroy');

            // Borrar productos - solo admin
            Route::delete('/productos/{producto}', [ProductosController::class, 'destroy'])->name('productos.destroy');

            // Gestión completa de usuarios - solo admin
            Route::resource('users', UserController::class)->except(['index', 'show']);
        }); 

        Route::resource('categorias', CategoriasController::class);

        // Rutas de movimientos - controlar acceso a edición/eliminación
        Route::get('/movimientos', [MovimientosController::class, 'index'])->name('movimientos.index');
        Route::get('/movimientos/create', [MovimientosController::class, 'create'])->name('movimientos.create');
        Route::post('/movimientos', [MovimientosController::class, 'store'])->name('movimientos.store');
        Route::get('/movimientos/{movimiento}', [MovimientosController::class, 'show'])->name('movimientos.show');
        Route::get('/movimientos/get-estanterias', [MovimientosController::class, 'getEstanterias'])
            ->name('movimientos.get-estanterias');

        // Editar movimiento - usuario puede editar los suyos, admin puede editar todos
        Route::get('/movimientos/{movimiento}/edit', [MovimientosController::class, 'edit'])
            ->middleware('can:update,movimiento')
            ->name('movimientos.edit');

        Route::put('/movimientos/{movimiento}', [MovimientosController::class, 'update'])
            ->middleware('can:update,movimiento')
            ->name('movimientos.update');

        // Eliminar movimiento - usuario puede eliminar los suyos, admin puede eliminar todos
        Route::delete('/movimientos/{movimiento}', [MovimientosController::class, 'destroy'])
            ->middleware('can:delete,movimiento')
            ->name('movimientos.destroy');

        // Rutas de estanterías y zonas - ver para todos
        Route::get('/estanterias', [EstanteriasController::class, 'index'])->name('estanterias.index');
        Route::get('/estanterias/{estanteria}', [EstanteriasController::class, 'show'])->name('estanterias.show');

        Route::get('/zonas', [ZonasController::class, 'index'])->name('zonas.index');
        Route::get('/zonas/{zona}', [ZonasController::class, 'show'])->name('zonas.show');

        // Rutas de alertas - ver para todos
        Route::get('/alertas', [AlertaStockController::class, 'index'])->name('alertas.index');
        Route::get('/alertas/{alerta}', [AlertaStockController::class, 'show'])->name('alertas.show');

        // Rutas de productos - crear y editar para todos, eliminar solo admin
        Route::resource('productos', ProductosController::class)->except(['destroy']);

        // Rutas de almacén
        Route::get('/almacen', [AlmacenController::class, 'index'])->name('almacen.index');

        // Rutas de usuarios - ver para todos
        Route::get('/users', [UserController::class, 'index'])->name('users.index');
        Route::get('/users/{user}', [UserController::class, 'show'])->name('users.show');

        Route::view('perfil', 'profile')->name('perfil');
    });
});

require __DIR__ . '/auth.php';
