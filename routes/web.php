<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

// Importamos solo los controladores que tenemos listos
use App\Http\Controllers\UserController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ProfesorController;

// 1. Rutas de Autenticación (Públicas)
Auth::routes(['register' => true]);

// 2. Grupo Protegido (Solo usuarios logueados)
Route::middleware(['auth', 'no-back-history'])->group(function () {
    
    Route::get('/', [AdminController::class, 'index'])->name('admin.index');
    Route::resource('usuarios', UserController::class);
Route::get('/profesores', [UserController::class, 'index'])->name('profesores.index');
});


acomodar la duplicidad de las rutas de profesores y usuarios,
 asegurando que cada controlador maneje su propia lógica 
 sin interferencias.

 pues hay un conflicto entre UserController y ProfesorController, 
 ambos están intentando manejar la ruta '/profesores'. 
 Para resolver esto, debemos decidir qué controlador 
 se encargará de esa ruta específica.