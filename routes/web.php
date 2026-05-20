<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AdminController;

Auth::routes(['register' => true]);

Route::middleware(['auth', 'no-back-history'])->group(function () { //(Solo usuarios logueados)
    
    // Panel de inicio
    Route::get('/', [AdminController::class, 'index'])->name('admin.index');
    
    // CRUD de Usuarios (mantiene URLs como usuarios/create, usuarios/edit)
    Route::resource('usuarios', UserController::class);
    
    // Nuestra ruta especial para la tabla profesional de administración
    Route::get('/profesores', [UserController::class, 'index'])->name('profesores.index');
});