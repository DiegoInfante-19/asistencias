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
use Illuminate\Support\Facades\DB;

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
        $seccion = DB::transaction(function () use ($request) {
            $seccion = Seccion::create($request->safe()->except('pnfs'));
            $seccion->pnfs()->sync($request->validated('pnfs'));
            return $seccion;
        });
        
        return redirect()->route('estructura.periodos.secciones', ['periodo' => $seccion->id_periodo])
                         ->with('success', 'Sección académica creada exitosamente con los PNF asignados.');
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
            'pnfs',
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

        $pnfIds = $seccion->pnfs->pluck('id_pnf')->toArray();

        $estudiantesDisponibles = Persona::whereDoesntHave('inscripcionesSecciones', function ($q) {
                $q->where('estatus_inscripcion', 'Activo');
            })
            ->whereHas('titulacionPersona', function($q) use ($pnfIds) {
                $q->whereIn('id_pnf', $pnfIds);
            })
            ->where(function ($q) {
                $q->whereNull('continua_estudios')
                  ->orWhere('continua_estudios', '!=', 0);
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
        DB::transaction(function () use ($request, $seccion) {
            $seccion->update($request->safe()->except('pnfs'));
            
            if ($request->has('pnfs')) {
                $seccion->pnfs()->sync($request->validated('pnfs'));
            }
        });
        
        return redirect()->route('estructura.periodos.secciones', ['periodo' => $seccion->id_periodo])
                         ->with('success', 'Sección actualizada correctamente.');
    }

    public function destroy(Seccion $seccion): RedirectResponse
    {
        $idPeriodo = $seccion->id_periodo;

        if ($seccion->inscripciones()->withTrashed()->exists()) {
            return redirect()->route('estructura.periodos.secciones', ['periodo' => $idPeriodo])
                             ->with('error', 'No se puede eliminar la sección porque tiene un historial de estudiantes (incluso si fueron retirados).');
        }

        if ($seccion->profesores()->exists()) {
            return redirect()->route('estructura.periodos.secciones', ['periodo' => $idPeriodo])
                             ->with('error', 'No se puede eliminar la sección porque tiene profesores asignados. Remuévalos primero.');
        }

        if ($seccion->sesiones()->withTrashed()->exists()) {
            return redirect()->route('estructura.periodos.secciones', ['periodo' => $idPeriodo])
                             ->with('error', 'No se puede eliminar la sección porque ya cuenta con un historial de sesiones de clase.');
        }
        
        try {
            DB::transaction(function() use ($seccion) {
                // Desvinculamos las relaciones de la tabla pivote pnf_seccion
                $seccion->pnfs()->detach();
                $seccion->delete();
            });

            return redirect()->route('estructura.periodos.secciones', ['periodo' => $idPeriodo])
                             ->with('success', 'Sección eliminada con éxito.');
        } catch (\Illuminate\Database\QueryException $e) {
            return redirect()->route('estructura.periodos.secciones', ['periodo' => $idPeriodo])
                             ->with('error', 'Error de base de datos: Esta sección tiene registros vinculados que impiden su eliminación.');
        }
    }

    public function inscribirEstudiante(Request $request, Seccion $seccion): RedirectResponse
    {
        if (!$seccion->estaActiva()) {
            return back()->with('error', 'Acción denegada: Esta sección se encuentra inactiva y no admite nuevas inscripciones.');
        }

        $request->validate([
            'id_personas' => ['required', 'integer', 'exists:personas,id_personas']
        ], [
            'id_personas.required' => 'Debe seleccionar un participante.',
            'id_personas.exists'   => 'El participante seleccionado no existe en el sistema.'
        ]);

        $estudiante = Persona::with(['titulacionPersona.pnf', 'inscripcionesSecciones'])->findOrFail($request->id_personas);

        if ($estudiante->tieneInscripcionActiva()) {
            return back()->with('error', 'Acción denegada: El estudiante ya cuenta con una matrícula activa en otra sección. Debe ser retirado antes de registrar una nueva inscripción.');
        }

        if (!$estudiante->puede_continuar) {
            return back()->with('error', 'Acción denegada: Este estudiante ha sido marcado como "No Continúa" y tiene su matrícula bloqueada.');
        }

        $pnfEstudiante = $estudiante->titulacionPersona->id_pnf ?? null;
        if (!$pnfEstudiante || !$seccion->admitePnf($pnfEstudiante)) {
            return back()->with('error', 'Violación de regla: El Programa de Formación (PNF) del estudiante no coincide con ninguno de los PNF asociados a esta sección.');
        }

        $seccion->inscripciones()->create([
            'id_personas'         => $estudiante->id_personas,
            'fecha_inscripcion'   => now()->toDateString(),
            'estatus_inscripcion' => 'Activo'
        ]);

        return back()->with('success', 'Estudiante inscrito exitosamente en la sección.');
    }

    public function retirarEstudiante(Seccion $seccion, $id_inscripcion): RedirectResponse
    {
        if (!$seccion->estaActiva()) {
            return back()->with('error', 'Acción denegada: Esta sección se encuentra inactiva; su histórico está protegido.');
        }

        $inscripcion = $seccion->inscripciones()->findOrFail($id_inscripcion);
        $inscripcion->delete();

        return back()->with('success', 'Estudiante retirado de la sección.');
    }

    public function asignarProfesor(Request $request, Seccion $seccion): RedirectResponse
    {
        if (!$seccion->estaActiva()) {
            return back()->with('error', 'Acción denegada: No se pueden asignar profesores a secciones inactivas.');
        }

        $request->validate([
            'id_profesor' => 'required|exists:profesores,id_profesor'
        ]);

        $seccion->profesores()->syncWithoutDetaching([$request->id_profesor]);

        return back()->with('success', 'Profesor asignado a la sección exitosamente.');
    }

    public function removerProfesor(Seccion $seccion, $id_profesor): RedirectResponse
    {
        if (!$seccion->estaActiva()) {
            return back()->with('error', 'Acción denegada: La sección está inactiva y su carga docente está bloqueada.');
        }

        $seccion->profesores()->detach($id_profesor);

        return back()->with('success', 'Profesor removido de la sección correctamente.');
    }
}