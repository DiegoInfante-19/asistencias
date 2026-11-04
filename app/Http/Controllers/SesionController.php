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
use App\Http\Requests\StoreSesionMasivaRequest;
use App\DataTables\SeccionClasesDataTable;
use App\DataTables\SesionesPorSeccionDataTable;
use App\DataTables\AsistenciaSesionDataTable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\DB;
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
                abort(403, 'No posee autorización para ver las sesiones de esta sección.');
            }
        }

        $seccion->load(['periodoAcademico.cohorte', 'pnfs', 'profesores.user']);

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

        $secciones = Seccion::with(['periodoAcademico.cohorte', 'pnfs', 'profesores.user'])
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

        $seccion = Seccion::findOrFail($request->id_seccion);
        if (!$seccion->estaActiva()) {
            return redirect()->back()->withInput()->with('error', 'No se pueden programar sesiones en secciones inactivas.');
        }

        $user = Auth::user();

        if ($user->isProfesor()) {
            $profesorValido = Profesor::where('id_profesor', $request->id_profesor)
                ->whereHas('secciones', function ($query) use ($request) {
                    $query->where('secciones.id_seccion', $request->id_seccion);
                })->exists();

            if (!$profesorValido) {
                return redirect()->back()->withInput()->with('error', 'El profesor seleccionado no está asignado como docente de esta sección académica.');
            }
        }

        try {
            Sesion::create($request->validated());

            if (request()->ajax() || request()->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Sesión académica programada y registrada correctamente.'
                ]);
            }

            return redirect()->back()->with('success', 'Sesión académica programada y registrada correctamente.');
        } catch (\Exception $e) {
            if (request()->ajax() || request()->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error al programar la sesión: ' . $e->getMessage()
                ], 422);
            }

            return redirect()->back()->withInput()->with('error', 'Error al programar la sesión: ' . $e->getMessage());
        }
    }

    /**
     * GENERACIÓN MASIVA DE SESIONES
     */
    public function storeMasivo(StoreSesionMasivaRequest $request)
    {
        $seccion = Seccion::with('periodoAcademico')->findOrFail($request->id_seccion);

        // Autorizar enviando la entidad de la sección
        $this->authorize('create', [Sesion::class, $seccion]);

        $esAjax = $request->expectsJson() || $request->ajax() || $request->wantsJson();

        if (!$seccion->estaActiva()) {
            $msg = 'No se pueden programar sesiones en secciones inactivas.';
            return $esAjax ? response()->json(['success' => false, 'message' => $msg], 422) : redirect()->back()->with('error', $msg);
        }

        $user = Auth::user();
        if ($user->isProfesor()) {
            $profesorValido = Profesor::where('id_profesor', $request->id_profesor)
                ->whereHas('secciones', function ($query) use ($request) {
                    $query->where('secciones.id_seccion', $request->id_seccion);
                })->exists();

            if (!$profesorValido) {
                $msg = 'El profesor seleccionado no está asignado como docente de esta sección académica.';
                return $esAjax ? response()->json(['success' => false, 'message' => $msg], 422) : redirect()->back()->with('error', $msg);
            }
        }

        $periodo = $seccion->periodoAcademico;
        if (!$periodo || !$periodo->fecha_inicio || !$periodo->fecha_fin) {
            $msg = 'La sección no tiene un período académico válido asignado.';
            return $esAjax ? response()->json(['success' => false, 'message' => $msg], 422) : redirect()->back()->with('error', $msg);
        }

        $modo = $request->modo_generacion;
        $rangosFechas = [];

        // Normalizar los límites del período lectivo
        $inicioPeriodo = Carbon::parse($periodo->fecha_inicio)->startOfDay();
        $finPeriodo = Carbon::parse($periodo->fecha_fin)->endOfDay();

        if ($modo === 'periodo') {
            $rangosFechas[] = [
                'inicio' => $inicioPeriodo,
                'fin'    => $finPeriodo
            ];
        } elseif ($modo === 'meses' && is_array($request->input('meses'))) {
            foreach ($request->input('meses') as $anioMes) {
                $inicioMes = Carbon::parse($anioMes . '-01')->startOfMonth()->startOfDay();
                $finMes = $inicioMes->copy()->endOfMonth()->endOfDay();

                if ($inicioMes->lt($inicioPeriodo)) {
                    $inicioMes = $inicioPeriodo->copy();
                }
                if ($finMes->gt($finPeriodo)) {
                    $finMes = $finPeriodo->copy();
                }

                if ($inicioMes->lte($finMes)) {
                    $rangosFechas[] = ['inicio' => $inicioMes, 'fin' => $finMes];
                }
            }
        }

        $omitirPasadas = $request->has('omitir_pasadas');
        $omitirRecesos = $request->has('omitir_recesos');
        $omitirExistentes = $request->has('omitir_existentes');

        $hoy = Carbon::today()->format('Y-m-d');

        $recesos = $omitirRecesos
            ? PeriodoReceso::where('suspension_actividades', 1)->get()
            : collect();

        $sesionesExistentes = $omitirExistentes
            ? Sesion::where('id_seccion', $seccion->id_seccion)
                ->pluck('fecha_sesion')
                ->map(fn($f) => Carbon::parse($f)->format('Y-m-d'))
                ->toArray()
            : [];

        $fechasAGenerar = [];
        $fechasOmitidasPasadas = 0;
        $fechasOmitidasReceso = 0;
        $fechasOmitidasExistente = 0;

        foreach ($rangosFechas as $rango) {
            $cursor = $rango['inicio']->copy();
            $fin = $rango['fin'];

            while ($cursor->lte($fin)) {
                if ($cursor->dayOfWeek === Carbon::WEDNESDAY) {
                    $fechaStr = $cursor->format('Y-m-d');

                    if ($omitirPasadas && $fechaStr < $hoy) {
                        $fechasOmitidasPasadas++;
                        $cursor->addDay();
                        continue;
                    }

                    if ($omitirExistentes && in_array($fechaStr, $sesionesExistentes)) {
                        $fechasOmitidasExistente++;
                        $cursor->addDay();
                        continue;
                    }

                    if ($omitirRecesos) {
                        $caeEnReceso = $recesos->contains(function ($receso) use ($fechaStr) {
                            $inicioR = Carbon::parse($receso->fecha_inicio_periodo_receso)->format('Y-m-d');
                            $finR = Carbon::parse($receso->fecha_fin_periodo_receso)->format('Y-m-d');
                            return $fechaStr >= $inicioR && $fechaStr <= $finR;
                        });

                        if ($caeEnReceso) {
                            $fechasOmitidasReceso++;
                            $cursor->addDay();
                            continue;
                        }
                    }

                    $fechasAGenerar[] = $fechaStr;
                }
                $cursor->addDay();
            }
        }

        $fechasAGenerar = array_unique($fechasAGenerar);

        if (empty($fechasAGenerar)) {
            $detalles = [];
            if ($fechasOmitidasPasadas > 0) $detalles[] = "Anteriores a hoy: {$fechasOmitidasPasadas}";
            if ($fechasOmitidasReceso > 0) $detalles[] = "Recesos: {$fechasOmitidasReceso}";
            if ($fechasOmitidasExistente > 0) $detalles[] = "Ya existentes: {$fechasOmitidasExistente}";

            $strDetalles = !empty($detalles) ? ' (' . implode(', ', $detalles) . ')' : '';
            $msg = "No se encontraron fechas de miércoles disponibles para programar.{$strDetalles}.";

            return $esAjax
                ? response()->json(['success' => false, 'message' => $msg], 422)
                : redirect()->back()->with('error', $msg);
        }

        DB::beginTransaction();
        try {
            $registros = [];
            $observacion = $request->observacion_generacion ?: 'Generación masiva de clases';
            $now = now();

            foreach ($fechasAGenerar as $fechaStr) {
                $registros[] = [
                    'id_seccion'         => $seccion->id_seccion,
                    'id_profesor'        => $request->id_profesor,
                    'fecha_sesion'       => $fechaStr,
                    'observacion_sesion' => $observacion,
                    'created_at'         => $now,
                    'updated_at'         => $now,
                ];
            }

            Sesion::insert($registros);
            DB::commit();

            $totalCreadas = count($registros);
            $mensajeExito = "¡Éxito! Se programaron automáticamente {$totalCreadas} sesión(es) académica(s).";
            
            $omitidasInfo = [];
            if ($fechasOmitidasPasadas > 0) $omitidasInfo[] = "{$fechasOmitidasPasadas} por ser pasadas";
            if ($fechasOmitidasReceso > 0) $omitidasInfo[] = "{$fechasOmitidasReceso} por receso";
            if ($fechasOmitidasExistente > 0) $omitidasInfo[] = "{$fechasOmitidasExistente} por estar registradas";

            if (!empty($omitidasInfo)) {
                $mensajeExito .= " (Se omitieron " . implode(', ', $omitidasInfo) . ").";
            }

            if ($esAjax) {
                return response()->json([
                    'success' => true,
                    'message' => $mensajeExito,
                    'total_creadas' => $totalCreadas
                ], 200);
            }

            return redirect()->back()->with('success', $mensajeExito);
        } catch (\Exception $e) {
            DB::rollBack();
            $msg = 'Error durante la generación masiva de sesiones: ' . $e->getMessage();
            return $esAjax
                ? response()->json(['success' => false, 'message' => $msg], 500)
                : redirect()->back()->with('error', $msg);
        }
    }

    public function update(StoreSesionRequest $request, $sesion)
    {
        $sesionItem = Sesion::with('seccion')->findOrFail($sesion);

        if (!$sesionItem->seccion->estaActiva()) {
            return redirect()->back()->with('error', 'No se puede modificar esta sesión porque su sección académica está inactiva.');
        }

        Gate::authorize('update', $sesionItem);

        if ($sesionItem->tieneAsistenciaRegistrada() && $request->fecha_sesion !== $sesionItem->fecha_sesion->toDateString()) {
            return redirect()->back()->with('error', 'No se puede modificar la fecha de esta sesión porque ya cuenta con registros de asistencia de estudiantes.');
        }

        try {
            $datosValidados = $request->validated();

            if ($sesionItem->tieneAsistenciaRegistrada()) {
                $datosValidados['fecha_sesion'] = $sesionItem->fecha_sesion;
            }

            $sesionItem->update($datosValidados);

            return redirect()->back()->with('success', 'Sesión de clase actualizada exitosamente.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error al actualizar la sesión: ' . $e->getMessage());
        }
    }

    public function show(Sesion $sesion, AsistenciaSesionDataTable $dataTable)
    {
        Gate::authorize('view', $sesion);

        $sesion->load(['seccion.periodoAcademico.cohorte', 'seccion.pnfs', 'profesor.user']);

        $asistenciasRegistradas = Asistencia::where('id_sesiones', $sesion->id_sesiones)
            ->get()
            ->mapWithKeys(function ($asistencia) {
                $estado = $asistencia->estado_asistencia;
                $valorEstado = $estado instanceof \App\Enums\EstadoAsistencia ? $estado->value : strtolower($estado ?? 'presente');

                return [$asistencia->id_inscripcion_seccion => $valorEstado];
            })
            ->toArray();

        $puedeEditar = true;
        $idPnf = $sesion->seccion->pnfs->first()?->id_pnf ?? null;

        $dataTable->withSesionData(
            $sesion->id_sesiones,
            $sesion->id_seccion,
            $idPnf,
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

        $sesion->delete();

        return redirect()->back()
            ->with('success', 'Sesión eliminada correctamente del calendario.');
    }
}