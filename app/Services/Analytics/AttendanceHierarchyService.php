<?php

namespace App\Services\Analytics;

use App\Models\Pnf;
use App\Models\InscripcionSeccion;
use App\Models\PeriodoAcademico;
use App\Models\PeriodoReceso;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

class AttendanceHierarchyService
{
    public function obtenerJerarquiaPnfEmpresas(Request $request): array
    {
        // 1. Obtener PNF activos o filtrados
        $pnfsQuery = Pnf::query()->orderBy('nombre_pnf');
        if ($request->filled('pnf')) {
            $pnfsQuery->where('id_pnf', $request->pnf);
        }
        $pnfs = $pnfsQuery->get();

        // 2. Calcular los Miércoles Hábiles del Período Activo (FASE 1)
        // Estructura: ['2026-10' => ['nombre' => 'OCTUBRE 2026', 'total_clases' => 4, 'fechas' => ['2026-10-07', ...]]]
        $calendarioMiercoles = $this->calcularMiercolesHabilesPorMes();

        // Extraemos un arreglo simple para los encabezados de la tabla ['2026-10' => 'OCTUBRE 2026']
        $mesesPeriodo = [];
        foreach ($calendarioMiercoles as $mesKey => $data) {
            $mesesPeriodo[$mesKey] = $data['nombre'];
        }

        $estructuraFinal = [];

        foreach ($pnfs as $pnf) {
            // FASE 1.1: Filtrar estricto por Período Académico Activo
            $inscripcionesQuery = InscripcionSeccion::with([
                'persona.empresaPersona.empresa',
                'seccion.periodoAcademico',
                'asistencias.sesion'
            ])
                ->where('estatus_inscripcion', 'Activo')
                ->whereHas('seccion.pnfs', function ($q) use ($pnf) {
                    $q->where('pnfs.id_pnf', $pnf->id_pnf);
                })
                ->whereHas('seccion.periodoAcademico', function ($q) {
                    $q->where('estatus_periodo', 'Activo');
                });

            if ($request->filled('cohorte')) {
                $inscripcionesQuery->whereHas('seccion.periodoAcademico', function ($q) use ($request) {
                    $q->where('id_cohortes', $request->cohorte);
                });
            }
            if ($request->filled('seccion')) {
                $inscripcionesQuery->where('id_seccion', $request->seccion);
            }
            if ($request->filled('empresa')) {
                $inscripcionesQuery->whereHas('persona.empresaPersona', function ($q) use ($request) {
                    $q->where('id_empresa', $request->empresa);
                });
            }

            $inscripciones = $inscripcionesQuery->get();

            if ($inscripciones->isEmpty()) {
                continue;
            }

            $empresasAgrupadas = [];
            $estudiantesTotalesPnf = 0;
            $acumuladoMesesPnf = array_fill_keys(array_keys($mesesPeriodo), 0);
            $uapMesesPnf = array_fill_keys(array_keys($mesesPeriodo), 0);

            // Agrupar por Empresa
            $porEmpresa = $inscripciones->groupBy(function ($inscripcion) {
                $empresaPersona = optional($inscripcion->persona)->empresaPersona;
                return $empresaPersona && $empresaPersona->empresa
                    ? $empresaPersona->empresa->nombre_empresa
                    : 'Sin Empresa Asignada';
            });

            foreach ($porEmpresa as $nombreEmpresa => $listaInscripciones) {
                $cantidadTrabajadores = $listaInscripciones->count();
                $estudiantesTotalesPnf += $cantidadTrabajadores;

                $asistenciasPorMesEmpresa = array_fill_keys(array_keys($mesesPeriodo), 0);
                $estudiantesDetalle = []; // Array anidado para la fila desplegable de estudiantes

                foreach ($listaInscripciones as $ins) {
                    $persona = $ins->persona;
                    $asistenciasEstudiante = array_fill_keys(array_keys($mesesPeriodo), 0);

                    // Mapa de estatus por día para el semáforo y desglose (Ej: '2026-10-07' => 'presente')
                    $estatusPorDia = [];

                    // Recorremos las asistencias registradas del estudiante
                    foreach ($ins->asistencias as $asistencia) {
                        if ($asistencia->sesion && $asistencia->sesion->fecha_sesion) {
                            $fechaSesion = Carbon::parse($asistencia->sesion->fecha_sesion);
                            $mesKey = $fechaSesion->format('Y-m');
                            $fechaStr = $fechaSesion->format('Y-m-d');
                            $estado = strtolower($asistencia->estado_asistencia);

                            // Registrar el estado para el desglose diario (Nivel 6)
                            $estatusPorDia[$fechaStr] = $estado;

                            // Si la sesión pertenece a un mes de nuestro calendario de miércoles hábiles
                            if (isset($calendarioMiercoles[$mesKey])) {
                                // Asistencias válidas (Aval)
                                if (in_array($estado, ['presente', 'tarde', 'justificada'])) {
                                    $asistenciasEstudiante[$mesKey]++;
                                    $asistenciasPorMesEmpresa[$mesKey]++;
                                    $acumuladoMesesPnf[$mesKey]++;
                                }
                            }
                        }
                    }

                    // --- CÁLCULO CENTRALIZADO DE MÉTRICAS (TRAIT) ---
                    // Delegamos la responsabilidad matemática al modelo, asegurando igualdad total con Datatables
                    $metricas = $ins->metricas_semaforo;

                    $estudiantesDetalle[] = [
                        'cedula' => $persona->cedula_personas ?? 'N/A', 
                        'nombre' => $persona->nombre_completo ?? trim(($persona->primer_nombre_persona ?? '') . ' ' . ($persona->primer_apellido_persona ?? '')), 
                        'asistencias_meses' => $asistenciasEstudiante,
                        'estatus_diario' => $estatusPorDia,
                        'metricas' => $metricas // Array unificado: texto, clase_css, porcentaje
                    ];
                }

                // Calcular el Universo de Asistencias Posibles (UAP) para la Empresa en cada mes
                $porcentajeEmpresa = [];
                foreach ($mesesPeriodo as $mesKey => $mesNombre) {
                    $clasesDelMes = $calendarioMiercoles[$mesKey]['total_clases'] ?? 0;
                    $uapMes = $cantidadTrabajadores * $clasesDelMes; // FASE 2.1: UAP = E * Mh

                    $uapMesesPnf[$mesKey] += $uapMes; // Sumar al UAP general del PNF

                    $porcentajeEmpresa[$mesKey] = $uapMes > 0
                        ? round(($asistenciasPorMesEmpresa[$mesKey] / $uapMes) * 100, 2)
                        : null; // null si el mes tuvo 0 clases
                }

                $empresasAgrupadas[] = [
                    'nombre_empresa' => $nombreEmpresa,
                    'cantidad_trabajadores' => $cantidadTrabajadores,
                    'asistencias_meses' => $asistenciasPorMesEmpresa,
                    'porcentaje_meses' => $porcentajeEmpresa,
                    'estudiantes' => $estudiantesDetalle // FASE 2.2: Jerarquía anidada lista
                ];
            }

            // FASE 2.1: Calcular Porcentaje de Participación por Mes para el PNF usando UAP
            $porcentajeParticipacionMeses = [];
            foreach ($mesesPeriodo as $mesKey => $mesNombre) {
                $totalAsistenciasMes = $acumuladoMesesPnf[$mesKey];
                $uapPnfMes = $uapMesesPnf[$mesKey];

                $porcentajeParticipacionMeses[$mesKey] = $uapPnfMes > 0
                    ? round(($totalAsistenciasMes / $uapPnfMes) * 100, 2)
                    : null; // null indicará 0 clases en la vista
            }

            $estructuraFinal[] = [
                'id_pnf' => $pnf->id_pnf,
                'nombre_pnf' => $pnf->nombre_pnf,
                'total_estudiantes' => $estudiantesTotalesPnf,
                'meses_periodo' => $mesesPeriodo,
                'calendario_dias' => $calendarioMiercoles, // Pasamos el detalle de los días a la vista
                'empresas' => $empresasAgrupadas,
                'totales_meses_pnf' => $acumuladoMesesPnf,
                'porcentaje_participacion_meses' => $porcentajeParticipacionMeses
            ];
        }

        return $estructuraFinal;
    }

    /**
     * FASE 1.2: Calcula los miércoles hábiles reales basados en el período activo y los recesos.
     */
    private function calcularMiercolesHabilesPorMes(): array
    {
        $periodo = PeriodoAcademico::where('estatus_periodo', 'Activo')->first();

        if (!$periodo || !$periodo->fecha_inicio || !$periodo->fecha_fin) {
            return [];
        }

        $inicio = Carbon::parse($periodo->fecha_inicio);
        $fin = Carbon::parse($periodo->fecha_fin);

        // Obtener recesos que suspenden actividades dentro del rango
        $recesos = PeriodoReceso::where('suspension_actividades', 1)
            ->where(function ($q) use ($inicio, $fin) {
                $q->whereBetween('fecha_inicio_periodo_receso', [$inicio, $fin])
                    ->orWhereBetween('fecha_fin_periodo_receso', [$inicio, $fin]);
            })->get();

        $miercolesPorMes = [];
        $periodoDias = CarbonPeriod::create($inicio, $fin);

        foreach ($periodoDias as $fecha) {
            if ($fecha->dayOfWeek === Carbon::WEDNESDAY) {
                $fechaStr = $fecha->format('Y-m-d');
                $mesKey = $fecha->format('Y-m');

                // Verificar si este miércoles cae en un receso
                $esReceso = $recesos->contains(function ($r) use ($fechaStr) {
                    return $fechaStr >= $r->fecha_inicio_periodo_receso
                        && $fechaStr <= $r->fecha_fin_periodo_receso;
                });

                if (!isset($miercolesPorMes[$mesKey])) {
                    $miercolesPorMes[$mesKey] = [
                        'nombre' => mb_strtoupper($fecha->translatedFormat('F Y')),
                        'fechas' => [],
                        'total_clases' => 0
                    ];
                }

                if (!$esReceso) {
                    $miercolesPorMes[$mesKey]['fechas'][] = $fechaStr;
                    $miercolesPorMes[$mesKey]['total_clases'] = count($miercolesPorMes[$mesKey]['fechas']);
                }
            }
        }

        ksort($miercolesPorMes);
        return $miercolesPorMes;
    }
}