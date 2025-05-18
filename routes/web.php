<?php

use App\Http\Controllers\Admin\UserApprovalController;
use App\Http\Controllers\ViewsControllers\CategoriasController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ViewsControllers\DashboardController;
use App\Http\Controllers\ViewsControllers\MovimientosController;
use App\Http\Controllers\ViewsControllers\ProductosController;
use App\Http\Middleware\EnsureUserIsApproved;

Route::middleware(['auth'])->group(function () {
    Route::middleware([EnsureUserIsApproved::class])->group(function () {
        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

        Route::get('/movimientos/get-estanterias', [MovimientosController::class, 'getEstanterias'])
            ->name('movimientos.get-estanterias');

        Route::resource('movimientos', MovimientosController::class);
        Route::resource('productos', ProductosController::class);
        Route::resource('categorias', CategoriasController::class);

        Route::view('perfil', 'profile')->name('perfil');
    });
});

Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/users/solicitudes', [UserApprovalController::class, 'index'])->name('users.solicitudes');
    Route::post('/users/{user}/aprobar', [UserApprovalController::class, 'approve'])->name('users.aprobar');
});

require __DIR__ . '/auth.php';
