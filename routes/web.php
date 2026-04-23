<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\MiembroController;
use App\Http\Controllers\HomeController;

// Ruta principal protegida
Route::get('/',function (){ return view('index');})->middleware('auth');

// Autenticación (Registro desactivado)
Auth::routes(['register' => false]);

// Home
Route::get('/home', [HomeController::class, 'index'])->name('home');

// --- SECCIÓN DE MIEMBROS ---

Route::resource(name:'/miembros', controller:  \App\Http\Controllers\MiembroController::class);

