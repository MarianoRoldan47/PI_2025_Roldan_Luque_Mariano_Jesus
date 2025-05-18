<?php

use App\Http\Controllers\ViewsControllers\CategoriasController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ViewsControllers\DashboardController;
use App\Http\Controllers\ViewsControllers\MovimientosController;
use App\Http\Controllers\ViewsControllers\ProductosController;

Route::middleware(['auth'])->group(function () {
    // Ruta del dashboard
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // Ruta para obtener estanterías
    Route::get('/movimientos/get-estanterias', [MovimientosController::class, 'getEstanterias'])
        ->name('movimientos.get-estanterias');

    // Rutas de recursos
    Route::resource('movimientos', MovimientosController::class);
    Route::resource('productos', ProductosController::class);
    Route::resource('categorias', CategoriasController::class);

    // Ruta del perfil
    Route::view('profile', 'profile')->name('profile');
});

require __DIR__ . '/auth.php';
