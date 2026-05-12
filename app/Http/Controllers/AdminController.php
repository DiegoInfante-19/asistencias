<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Asistencia;

class AdminController extends Controller
{
    public function __construct()
    {
        // Esto obliga a que cualquier persona que entre aquí deba estar logueada
        $this->middleware('auth');
    }

    public function index()
{
    // 1. Obtenemos los usuarios para el contador
    $usuarios = \App\Models\User::all();
    
    // 2. Como borraste Miembros, Ministerios y Asistencias,
    // enviamos arreglos vacíos [] para que los @foreach de tu vista no den error.
    $ministerios = []; 
    $miembros = [];
    $asistencias = [];

    // 3. CAMBIO CLAVE: Quitamos "admin." y dejamos solo "index"
    return view('index', compact('usuarios', 'ministerios', 'miembros', 'asistencias'));
}
}