<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        // Obtenemos solo los usuarios necesarios
        $usuarios = User::all();

        // Retornamos la vista index sin las variables obsoletas
        return view('index', compact('usuarios'));
    }
}