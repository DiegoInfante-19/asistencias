<?php

namespace App\Http\Controllers;

use App\Models\Seccion;
use App\Models\PeriodoAcademico;
use App\Models\Pnf;
use App\Models\Profesor;
use App\Models\Empresa;
use App\Models\Persona;
use App\DataTables\SeccionDataTable;
use App\DataTables\InscripcionSeccionDataTable;
use App\DataTables\ProfesorSeccionDataTable;
use App\DataTables\SesionesSeccionDataTable; 
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
        $seccion = Seccion::create($request->validated());
        
        // CORRECCIÓN: Redirigir a la vista de las secciones de ESE periodo
        return redirect()->route('estructura.periodos.secciones', ['periodo' => $seccion->id_periodo])
                         ->with('success', 'Sección académica creada exitosamente.');
    }

    public function show(
        Seccion $seccion, 
        InscripcionSeccionDataTable $dataTable, 
        ProfesorSeccionDataTable $profesorDataTable, 
        SesionesSeccionDataTable $sesionesDataTable
    ) {
        if (request()->ajax() || request()->wantsJson()) {
            $table = request()->get('table');
            
            if ($table === 'docentes-table') {
                return $profesorDataTable->with('seccion', $seccion)->ajax();
            }
            if ($table === 'sesiones-seccion-table') {
                return $sesionesDataTable->with('id_seccion', $seccion->id_seccion)->ajax();
            }
            return $dataTable->with('seccion', $seccion)->ajax();
        }

        $seccion->load([
            'periodoAcademico.cohorte', 
            'pnf', 
            'profesores.user', 
            'profesores.pnf', 
            'inscripciones.persona.lugarNacimiento.ciudad.estado',
            'inscripciones.persona.titulacionPersona.pnf',
            'inscripciones.persona.empresaPersona.empresa',
            'sesiones' => function($query) {
                $query->orderBy('fecha_sesion', 'desc');
            },
            'sesiones.profesor.user',
            'sesiones.asistencias.inscripcionSeccion.persona'
        ]);

        $estudiantesDisponibles = Persona::whereDoesntHave('inscripcionesSecciones')
            ->whereHas('titulacionPersona', function($q) use ($seccion) {
                $q->where('id_pnf', $seccion->id_pnf);
            })
            ->with(['titulacionPersona.pnf', 'cohorte'])
            ->get();

        $profesoresDisponibles = Profesor::with(['user', 'pnf'])
            ->whereDoesntHave('secciones', function ($q) use ($seccion) {
                $q->where('secciones.id_seccion', $seccion->id_seccion);
            })
            ->get();

        return view('secciones.show', compact('seccion', 'estudiantesDisponibles', 'profesoresDisponibles'))
            ->with([
                'dataTable' => $dataTable->with('seccion', $seccion),
                'profesorDataTable' => $profesorDataTable->with('seccion', $seccion),
                'sesionesDataTable' => $sesionesDataTable->with('id_seccion', $seccion->id_seccion)
            ]);
    }

    public function update(UpdateSeccionRequest $request, Seccion $seccion): RedirectResponse
    {
        $seccion->update($request->validated());
        
        // CORRECCIÓN: Redirigir a la vista de las secciones de ESE periodo
        return redirect()->route('estructura.periodos.secciones', ['periodo' => $seccion->id_periodo])
                         ->with('success', 'Sección actualizada correctamente.');
    }

    public function destroy(Seccion $seccion): RedirectResponse
    {
        $idPeriodo = $seccion->id_periodo;

        // 1. Validar inscripciones INCLUYENDO a los estudiantes retirados (Soft Deletes)
        if ($seccion->inscripciones()->withTrashed()->exists()) {
            return redirect()->route('estructura.periodos.secciones', ['periodo' => $idPeriodo])
                             ->with('error', 'No se puede eliminar la sección porque tiene un historial de estudiantes (incluso si fueron retirados).');
        }

        // 2. Validar profesores asignados
        if ($seccion->profesores()->exists()) {
            return redirect()->route('estructura.periodos.secciones', ['periodo' => $idPeriodo])
                             ->with('error', 'No se puede eliminar la sección porque tiene profesores asignados. Remuévalos primero.');
        }

        // 3. Validar historial de clases/sesiones INCLUYENDO las canceladas/eliminadas (Soft Deletes)
        if ($seccion->sesiones()->withTrashed()->exists()) {
            return redirect()->route('estructura.periodos.secciones', ['periodo' => $idPeriodo])
                             ->with('error', 'No se puede eliminar la sección porque ya cuenta con un historial de sesiones de clase.');
        }
        
        try {
            $seccion->delete();
            return redirect()->route('estructura.periodos.secciones', ['periodo' => $idPeriodo])
                             ->with('success', 'Sección eliminada con éxito.');
        } catch (\Illuminate\Database\QueryException $e) {
            return redirect()->route('estructura.periodos.secciones', ['periodo' => $idPeriodo])
                             ->with('error', 'Error de base de datos: Esta sección tiene registros vinculados que impiden su eliminación.');
        }
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

    public function asignarProfesor(Request $request, Seccion $seccion): RedirectResponse
    {
        $request->validate([
            'id_profesor' => 'required|exists:profesores,id_profesor'
        ]);

        $seccion->profesores()->syncWithoutDetaching([$request->id_profesor]);

        return back()->with('success', 'Profesor asignado a la sección exitosamente.');
    }

    public function removerProfesor(Seccion $seccion, $id_profesor): RedirectResponse
    {
        $seccion->profesores()->detach($id_profesor);

        return back()->with('success', 'Profesor removido de la sección correctamente.');
    }
}