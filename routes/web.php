<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

// Importamos solo los controladores que tenemos listos
use App\Http\Controllers\UserController;
use App\Http\Controllers\AdminController;

// 1. Rutas de Autenticación (Públicas)
Auth::routes(['register' => true]);

// 2. Grupo Protegido (Solo usuarios logueados)
Route::middleware(['auth', 'no-back-history'])->group(function () {
    
    Route::get('/', [AdminController::class, 'index'])->name('admin.index');
    Route::resource('usuarios', UserController::class);

});