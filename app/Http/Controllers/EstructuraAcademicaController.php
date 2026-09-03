<?php

namespace App\Http\Controllers;

use App\Models\Cohorte;
use App\Models\Pnf;
use App\Models\Profesor;
use App\Models\Empresa;
use Illuminate\Http\Request;
use Illuminate\View\View;

class EstructuraAcademicaController extends Controller
{
    /**
     * Muestra la vista unificada de la estructura académica.
     * Carga las cohortes con sus respectivos periodos, secciones y PNFs asociados.
     */
    public function index(): View
    {
        $cohortes = Cohorte::with(['periodosAcademicos.secciones.pnf'])
            ->orderBy('id_cohortes', 'desc')
            ->get();
            
        $pnfs = Pnf::all();
        $profesores = Profesor::with('user')->get();
        $empresas = Empresa::all();

        return view('estructura_academica.index', compact('cohortes', 'pnfs', 'profesores', 'empresas'));
    }
}