<?php

namespace App\Http\Controllers;

use App\Models\Sesion;
use App\Models\Seccion;
use App\Models\Profesor;
use App\Models\InscripcionSeccion;
use App\Models\Asistencia;
use App\Models\PeriodoReceso;
use App\Models\Pnf;
use App\Models\Empresa;
use App\Models\Titulo;
use App\Http\Requests\StoreSesionRequest;
use App\DataTables\SeccionClasesDataTable;
use App\DataTables\SesionesPorSeccionDataTable;
use App\DataTables\AsistenciaSesionDataTable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Http\Request;
use Carbon\Carbon;

class SesionController extends Controller
{
    public function seccionesIndex(SeccionClasesDataTable $dataTable)
    {
        Gate::authorize('viewAny', Sesion::class);

        /** @var \App\Models\User $user */
        $user = Auth::user();

        if (request()->ajax()) {
            return $dataTable->ajax();
        }

        $pnfs = Pnf::where('vigencia_pnf', 1)->orderBy('nombre_pnf')->get();
        $empresas = Empresa::orderBy('nombre_empresa')->get();
        $titulos = Titulo::all();

        $profesores = collect();
        if ($user->isAdmin() || $user->isCoordinador()) {
            $profesores = Profesor::with('user')->get()->sortBy(function ($p) {
                return ($p->user->name_users ?? '') . ' ' . ($p->user->last_name_users ?? '');
            });
        }

        return $dataTable->render('sesiones.secciones_index', compact('pnfs', 'empresas', 'titulos', 'profesores'));
    }

    public function sesionesPorSeccion(Seccion $seccion, SesionesPorSeccionDataTable $dataTable)
    {
        Gate::authorize('viewAny', Sesion::class);

        /** @var \App\Models\User $user */
        $user = Auth::user();

        if (!$user->isAdmin() && !$user->isCoordinador()) {
            $profesorId = $user->profesor ? $user->profesor->id_profesor : -1;
            $tieneAcceso = $seccion->profesores()->where('profesor_seccion.id_profesor', $profesorId)->exists();

            if (!$tieneAcceso) {
                abort(403, 'No posee autorizacion para ver las sesiones de esta seccion.');
            }
        }

        $seccion->load(['periodoAcademico.cohorte', 'pnf', 'profesores.user']);

        $periodosRecesos = PeriodoReceso::where('suspension_actividades', 1)
            ->select('fecha_inicio_periodo_receso', 'fecha_fin_periodo_receso')
            ->get();

        if (request()->ajax() || request()->wantsJson()) {
            return $dataTable->withIdSeccion($seccion->id_seccion)->ajax();
        }

        return $dataTable->withIdSeccion($seccion->id_seccion)->render('sesiones.por_seccion', compact('seccion', 'periodosRecesos'));
    }

    public function create(Request $request)
    {
        $this->authorize('create', Sesion::class);

        $seccionSeleccionadaId = $request->get('seccion_id');

        $secciones = Seccion::with(['periodoAcademico.cohorte', 'pnf', 'profesores.user'])
            ->where('estatus_seccion', 'Activa')
            ->get();

        $periodosRecesos = PeriodoReceso::where('suspension_actividades', 1)
            ->select('fecha_inicio_periodo_receso', 'fecha_fin_periodo_receso')
            ->get();

        return view('sesiones.create', compact('secciones', 'periodosRecesos', 'seccionSeleccionadaId'));
    }

    public function store(StoreSesionRequest $request)
    {
        $this->authorize('create', Sesion::class);

        $user = Auth::user();

        if ($user->isProfesor()) {
            $profesorValido = Profesor::where('id_profesor', $request->id_profesor)
                ->whereHas('secciones', function ($query) use ($request) {
                    $query->where('secciones.id_seccion', $request->id_seccion);
                })->exists();

            if (!$profesorValido) {
                return response()->json([
                    'success' => false,
                    'message' => 'El profesor seleccionado no está asignado como docente de esta sección académica.'
                ], 422);
            }
        }

        try {
            Sesion::create($request->validated());

            return response()->json([
                'success' => true,
                'message' => 'Sesión académica programada y registrada correctamente.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al programar la sesión: ' . $e->getMessage()
            ], 422);
        }
    }

    public function update(StoreSesionRequest $request, $sesion)
    {
        $sesionItem = Sesion::findOrFail($sesion);

        Gate::authorize('update', $sesionItem);

        // BLINDAJE CRÍTICO: Si ya tiene asistencia, NUNCA se puede cambiar la fecha
        if ($sesionItem->tieneAsistenciaRegistrada() && $request->fecha_sesion !== $sesionItem->fecha_sesion->toDateString()) {
            return response()->json([
                'success' => false,
                'message' => 'No se puede modificar la fecha de esta sesión porque ya cuenta con registros de asistencia de estudiantes.'
            ], 422);
        }

        try {
            $datosValidados = $request->validated();

            // Si ya tiene asistencia, aseguramos mantener la fecha original independientemente de lo enviado
            if ($sesionItem->tieneAsistenciaRegistrada()) {
                $datosValidados['fecha_sesion'] = $sesionItem->fecha_sesion;
            }

            $sesionItem->update($datosValidados);

            return response()->json([
                'success' => true,
                'message' => 'Sesión de clase actualizada exitosamente.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar la sesión: ' . $e->getMessage()
            ], 422);
        }
    }

    public function show(Sesion $sesion, AsistenciaSesionDataTable $dataTable)
    {
        Gate::authorize('view', $sesion);

        $sesion->load(['seccion.periodoAcademico.cohorte', 'seccion.pnf', 'profesor.user']);

        // SOLUCIÓN: Mapeamos asegurando extraer el string plano ('presente', 'ausente', 'justificada')
        $asistenciasRegistradas = Asistencia::where('id_sesiones', $sesion->id_sesiones)
            ->get()
            ->mapWithKeys(function ($asistencia) {
                $estado = $asistencia->estado_asistencia;
                $valorEstado = $estado instanceof \App\Enums\EstadoAsistencia ? $estado->value : strtolower($estado ?? 'presente');
                
                return [$asistencia->id_inscripcion_seccion => $valorEstado];
            })
            ->toArray();

        $user = Auth::user();
        $puedeEditar = true;
        
        if (!$user->isAdmin() && !is_null($user->isCoordinador()) && !$user->isCoordinador()) {
            $puedeEditar = !$sesion->estaCerrada();
        }

        $dataTable->withSesionData(
            $sesion->id_sesiones,
            $sesion->id_seccion,
            $sesion->seccion->id_pnf,
            $asistenciasRegistradas,
            $puedeEditar
        );

        if (request()->ajax() || request()->wantsJson()) {
            return $dataTable->ajax();
        }

        $totalInscritos = InscripcionSeccion::where('id_seccion', $sesion->id_seccion)
            ->where('estatus_inscripcion', 'Activo')
            ->count();

        $inscripciones = InscripcionSeccion::with(['persona.cohorte', 'persona.titulacionPersona.titulacion'])
            ->where('id_seccion', $sesion->id_seccion)
            ->where('estatus_inscripcion', 'Activo')
            ->get()
            ->sortBy(function ($inscripcion) {
                return $inscripcion->persona->nombre_completo ?? $inscripcion->persona->cedula_personas;
            });

        return view('sesiones.show', compact('sesion', 'inscripciones', 'asistenciasRegistradas', 'totalInscritos', 'puedeEditar', 'dataTable'));
    }

    public function destroy(Sesion $sesion)
    {
        Gate::authorize('delete', $sesion);

        if ($sesion->tieneAsistenciaRegistrada()) {
            return redirect()->back()->with('error', 'No se puede eliminar esta sesión porque ya tiene asistencias registradas.');
        }

        $idSeccion = $sesion->id_seccion;
        $sesion->delete();

        return redirect()->route('clases.secciones.sesiones', $idSeccion)
            ->with('success', 'Sesión eliminada correctamente del calendario.');
    }
}