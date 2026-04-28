<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\MiembroController;
use App\Http\Controllers\HomeController;

// Ruta principal protegida
// Route::get('/',function (){ return view('index');})->middleware('auth');


Route::get('/', [\App\Http\Controllers\AdminController::class, 'index'])->name('home');

// Autenticación (Registro desactivado)
Auth::routes(['register' => false]);

// Home
Route::get('/home', [HomeController::class, 'index']);

// --- SECCIÓN DE MIEMBROS ---

Route::resource(name:'/miembros', controller:  \App\Http\Controllers\MiembroController::class);
Route::resource(name:'/ministerios', controller:  \App\Http\Controllers\MinisterioController::class);
