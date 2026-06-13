<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function index()
    {
        // Obtenemos los datos del usuario autenticado
        $user = Auth::user();
        
        // Retornamos la vista y le pasamos los datos del usuario
        return view('perfil.index', compact('user'));
    }
}
