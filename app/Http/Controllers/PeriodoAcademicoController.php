<?php

namespace App\Http\Controllers;

use App\Models\PeriodoAcademico;
use App\Models\Cohorte;
use App\Http\Requests\StorePeriodoAcademicoRequest;
use App\Http\Requests\UpdatePeriodoAcademicoRequest;
use App\DataTables\PeriodosAcademicosDataTable; // <-- NUEVO
use Illuminate\Http\Request;

class PeriodoAcademicoController extends Controller
{
    public function index(PeriodosAcademicosDataTable $dataTable) 
    {
        // Blindaje AJAX para DataTables
        if (request()->ajax() || request()->wantsJson()) {
            return $dataTable->ajax();
        }

        $cohortes = Cohorte::all();
        return $dataTable->render('periodos_academicos.index', compact('cohortes'));
    }

    // (El método create() se puede eliminar si usamos modales, o dejarlo como fallback)
    
    public function store(StorePeriodoAcademicoRequest $request)
    {
        $periodo = PeriodoAcademico::create($request->validated());

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Período académico aperturado exitosamente.',
                'data'    => $periodo
            ]);
        }

        return redirect()->route('periodos-academicos.index')
                         ->with('success', 'Período académico creado exitosamente.');
    }

    public function update(UpdatePeriodoAcademicoRequest $request, PeriodoAcademico $periodo)
    {
        $periodo->update($request->validated());

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Período académico actualizado correctamente.'
            ]);
        }

        return redirect()->route('periodos-academicos.index')
                         ->with('success', 'Período académico actualizado correctamente.');
    }

    // Los métodos show() y destroy() se mantienen exactamente iguales a tu código.
}