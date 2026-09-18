<?php

namespace App\Http\Controllers;

use App\Models\PeriodoAcademico;
use App\Models\Pnf;
use App\Models\Profesor;
use Illuminate\Http\Request;
use App\DataTables\CohortesDataTable; 
use App\DataTables\SeccionDataTable; // CORREGIDO: Singular, coincide con tu archivo real

class EstructuraAcademicaController extends Controller
{
    /**
     * NIVEL 1: Muestra la vista principal con el DataTable de Cohortes y Períodos.
     */
    public function index(CohortesDataTable $dataTable)
    {
        if (request()->ajax() || request()->wantsJson()) {
            return $dataTable->ajax();
        }

        return $dataTable->render('estructura_academica.index');
    }

    /**
     * NIVEL 2: Muestra las secciones correspondientes a un período específico.
     */
    public function seccionesPorPeriodo(PeriodoAcademico $periodo, SeccionDataTable $dataTable) // CORREGIDO: Singular
    {
        // Blindaje AJAX para el Nivel 2
        if (request()->ajax() || request()->wantsJson()) {
            return $dataTable->with('id_periodo', $periodo->id_periodo)->ajax();
        }

        $periodo->load('cohorte');

        $pnfs = Pnf::all();
        $profesores = Profesor::with('user')->get();

        return $dataTable->with('id_periodo', $periodo->id_periodo)
                         ->render('estructura_academica.secciones_periodo', compact('periodo', 'pnfs', 'profesores'));
    }
}