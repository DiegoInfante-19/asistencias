<?php

namespace App\Http\Controllers;

use App\Models\Seccion;
use App\Models\PeriodoAcademico;
use App\Models\Pnf;
use App\Models\Profesor;
use App\Models\Empresa;
use App\Models\Persona;
use App\DataTables\SeccionDataTable;
use App\Http\Requests\StoreSeccionRequest;
use App\Http\Requests\UpdateSeccionRequest;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class SeccionController extends Controller
{
    public function index(SeccionDataTable $dataTable)
    {
        if (request()->ajax() || request()->wantsJson()) {
            return $dataTable->ajax();
        }

        $pnfs = Pnf::all();
        $profesores = Profesor::with('user')->get();
        $empresas = Empresa::all();
        $periodos = PeriodoAcademico::with('cohorte')->get();

        return $dataTable->render('secciones.index', compact('pnfs', 'profesores', 'empresas', 'periodos'));
    }

    public function store(StoreSeccionRequest $request): RedirectResponse
    {
        Seccion::create($request->validated());
        return redirect()->route('estructura.index')->with('success', 'Sección académica creada exitosamente.');
    }

    public function show(Seccion $seccion): View
    {
        $seccion->load([
            'periodoAcademico.cohorte', 
            'pnf', 
            'profesores.user', 
            'inscripciones.persona.empresaPersona.empresa',
            'inscripciones.persona.cohorte',
            'sesiones' => function($query) {
                $query->orderBy('fecha_sesion', 'desc');
            },
            'sesiones.profesor.user',
            'sesiones.asistencias.inscripcionSeccion.persona'
        ]);

        $estudiantesYaInscritos = $seccion->inscripciones->pluck('id_personas');
        
        $estudiantesDisponibles = Persona::whereNotIn('id_personas', $estudiantesYaInscritos)
            ->whereHas('titulacionPersona', function($q) use ($seccion) {
                $q->where('id_pnf', $seccion->id_pnf);
            })
            ->with(['cohorte', 'empresaPersona.empresa'])
            ->get();

        return view('secciones.show', compact('seccion', 'estudiantesDisponibles'));
    }

    public function update(UpdateSeccionRequest $request, Seccion $seccion): RedirectResponse
    {
        $seccion->update($request->validated());
        return redirect()->route('estructura.index')->with('success', 'Sección actualizada correctamente.');
    }

    public function destroy(Seccion $seccion): RedirectResponse
    {
        if ($seccion->inscripciones()->exists()) {
            return redirect()->route('estructura.index')->withErrors(['error' => 'No se puede eliminar la sección porque cuenta con estudiantes inscritos.']);
        }
        $seccion->delete();
        return redirect()->route('estructura.index')->with('success', 'Sección eliminada con éxito.');
    }

    public function inscribirEstudiante(Request $request, Seccion $seccion): RedirectResponse
    {
        $request->validate([
            'id_personas' => 'required|exists:personas,id_personas'
        ]);

        $estudiante = Persona::with('titulacionPersona')->findOrFail($request->id_personas);
        
        if (!$estudiante->titulacionPersona || $estudiante->titulacionPersona->id_pnf !== $seccion->id_pnf) {
            return back()->with('error', 'Violación de regla: El estudiante pertenece a un PNF diferente al de esta sección.');
        }

        $seccion->inscripciones()->create([
            'id_personas' => $estudiante->id_personas,
            'fecha_inscripcion' => now(),
            'estatus_inscripcion' => 'Activo'
        ]);

        return back()->with('success', 'Estudiante inscrito exitosamente en la sección.');
    }

    public function retirarEstudiante(Seccion $seccion, $id_inscripcion): RedirectResponse
    {
        $inscripcion = $seccion->inscripciones()->findOrFail($id_inscripcion);
        $inscripcion->delete();

        return back()->with('success', 'Estudiante retirado de la sección.');
    }
}