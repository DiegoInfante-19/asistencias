<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\MiembroController;
use App\Http\Controllers\UserController;

use App\Http\Controllers\HomeController;


// Ruta principal protegida
// Route::get('/',function (){ return view('index');})->middleware('auth');


Route::get('/', [\App\Http\Controllers\AdminController::class, 'index'])->name('home');


Auth::routes(['register' => true]);

// Home
Route::get('/home', [HomeController::class, 'index']);

// --- SECCIÓN DE MIEMBROS ---

Route::resource(name:'/miembros',    controller:  \App\Http\Controllers\MiembroController::class);
Route::resource(name:'/ministerios', controller:  \App\Http\Controllers\MinisterioController::class);
Route::resource(name:'/usuarios',    controller:  \App\Http\Controllers\UserController::class); 

