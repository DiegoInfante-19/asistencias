<?php

namespace App\Http\Controllers;

use App\Models\PeriodoAcademico;
use App\Models\Cohorte;
use App\Http\Requests\StorePeriodoAcademicoRequest;
use App\Http\Requests\UpdatePeriodoAcademicoRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class PeriodoAcademicoController extends Controller
{
    public function index(): View
    {
        $periodos = PeriodoAcademico::with('cohorte')->latest('id_periodo')->paginate(10);
        $cohortes = Cohorte::all();

        return view('periodos_academicos.index', compact('periodos', 'cohortes'));
    }

    public function create(): View
    {
        $cohortes = Cohorte::all();
        return view('periodos_academicos.create', compact('cohortes'));
    }

    public function store(StorePeriodoAcademicoRequest $request): RedirectResponse
    {
        PeriodoAcademico::create($request->validated());

        return redirect()->route('periodos-academicos.index')
                         ->with('success', 'Período académico creado exitosamente.');
    }

    public function show(PeriodoAcademico $periodo): View
    {
        $periodo->load('secciones.pnf');
        return view('periodos_academicos.show', compact('periodo'));
    }

    public function edit(PeriodoAcademico $periodo): View
    {
        $cohortes = Cohorte::all();
        return view('periodos_academicos.edit', compact('periodo', 'cohortes'));
    }

    public function update(UpdatePeriodoAcademicoRequest $request, PeriodoAcademico $periodo): RedirectResponse
    {
        $periodo->update($request->validated());

        return redirect()->route('periodos-academicos.index')
                         ->with('success', 'Período académico actualizado correctamente.');
    }

    public function destroy(PeriodoAcademico $periodo): RedirectResponse
    {
        if ($periodo->secciones()->exists()) {
            return back()->withErrors(['error' => 'No se puede eliminar el período porque tiene secciones académicas asociadas.']);
        }

        $periodo->delete();

        return redirect()->route('periodos-academicos.index')
                         ->with('success', 'Período académico eliminado con éxito.');
    }
}