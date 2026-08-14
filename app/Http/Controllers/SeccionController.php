<?php

namespace App\Http\Controllers;

use App\Models\Seccion;
use App\Models\PeriodoAcademico;
use App\Models\Pnf;
use App\Models\Profesor;
use App\Http\Requests\StoreSeccionRequest;
use App\Http\Requests\UpdateSeccionRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class SeccionController extends Controller
{
    public function index(): View
    {
        $secciones = Seccion::with(['periodoAcademico.cohorte', 'pnf', 'profesores'])->latest('id_seccion')->paginate(10);
        $periodos = PeriodoAcademico::with('cohorte')->get();
        $pnfs = Pnf::all();
        $profesores = Profesor::with('user')->get();

        return view('secciones.index', compact('secciones', 'periodos', 'pnfs', 'profesores'));
    }

    public function create(): View
    {
        $periodos = PeriodoAcademico::with('cohorte')->get();
        $pnfs = Pnf::all();

        return view('secciones.create', compact('periodos', 'pnfs'));
    }

    public function store(StoreSeccionRequest $request): RedirectResponse
    {
        Seccion::create($request->validated());

        return redirect()->route('secciones.index')
                         ->with('success', 'Sección académica creada exitosamente.');
    }

    public function show(Seccion $seccion): View
    {
        $seccion->load(['periodoAcademico.cohorte', 'pnf', 'profesores.user', 'inscripciones.persona', 'sesiones']);
        
        return view('secciones.show', compact('seccion'));
    }

    public function edit(Seccion $seccion): View
    {
        $periodos = PeriodoAcademico::with('cohorte')->get();
        $pnfs = Pnf::all();

        return view('secciones.edit', compact('seccion', 'periodos', 'pnfs'));
    }

    public function update(UpdateSeccionRequest $request, Seccion $seccion): RedirectResponse
    {
        $seccion->update($request->validated());

        return redirect()->route('secciones.index')
                         ->with('success', 'Sección actualizada correctamente.');
    }

    public function destroy(Seccion $seccion): RedirectResponse
    {
        if ($seccion->inscripciones()->exists()) {
            return back()->withErrors(['error' => 'No se puede eliminar la sección porque cuenta con estudiantes inscritos.']);
        }

        $seccion->delete();

        return redirect()->route('secciones.index')
                         ->with('success', 'Sección eliminada con éxito.');
    }
}