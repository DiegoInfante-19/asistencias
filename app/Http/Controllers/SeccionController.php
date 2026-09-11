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
use App\DataTables\SesionesSeccionDataTable; // <--- IMPORTACIÓN NUEVA
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

    // ACTUALIZACIÓN: Inyectamos la 3ra tabla (SesionesSeccionDataTable)
    public function show(
        Seccion $seccion, 
        InscripcionSeccionDataTable $dataTable, 
        ProfesorSeccionDataTable $profesorDataTable, 
        SesionesSeccionDataTable $sesionesDataTable
    ) {
        // INTERCEPTOR AJAX PARA MÚLTIPLES DATATABLES
        if (request()->ajax() || request()->wantsJson()) {
            $table = request()->get('table');
            
            if ($table === 'docentes-table') {
                return $profesorDataTable->with('seccion', $seccion)->ajax();
            }
            if ($table === 'sesiones-seccion-table') {
                return $sesionesDataTable->with('id_seccion', $seccion->id_seccion)->ajax();
            }
            // Por defecto, carga la tabla de estudiantes
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

        // RESTRICCIÓN ESTRICTA ESTUDIANTES
        $estudiantesDisponibles = Persona::whereDoesntHave('inscripcionesSecciones')
            ->whereHas('titulacionPersona', function($q) use ($seccion) {
                $q->where('id_pnf', $seccion->id_pnf);
            })
            ->with(['titulacionPersona.pnf', 'cohorte'])
            ->get();

        // RESTRICCIÓN ESTRICTA DOCENTES
        $profesoresDisponibles = Profesor::with(['user', 'pnf'])
            ->whereDoesntHave('secciones', function ($q) use ($seccion) {
                $q->where('secciones.id_seccion', $seccion->id_seccion);
            })
            ->get();

        // Enviamos las tres tablas empaquetadas a la vista
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

    // --- MÉTODOS DE MATRÍCULA DE ESTUDIANTES ---

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

    // --- MÉTODOS DE ASIGNACIÓN DE DOCENTES ---

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