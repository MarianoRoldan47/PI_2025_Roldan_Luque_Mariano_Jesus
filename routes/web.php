<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ViewsControllers\DashboardController;


Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

require __DIR__.'/auth.php';
