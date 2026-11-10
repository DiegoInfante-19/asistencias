<?php

namespace App\Http\Controllers;

use App\Models\Pnf;
use App\Models\Profesor;
use App\Models\Persona;
use App\Models\Empresa;
use App\Models\Seccion;
use App\Models\Sesion;
use App\Models\PeriodoAcademico;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Services\Analytics\AttendanceHierarchyService;

class DashboardController extends Controller
{
    public function index(AttendanceHierarchyService $hierarchyService)
    {
        $user = Auth::user();
        $esDirectivo = $user->isAdmin() || $user->isCoordinador();

        // Datos del período académico activo
        $periodoActual = PeriodoAcademico::whereIn('estatus_periodo', ['Activo', 'ACTIVO', 'activa', 'Activa'])
            ->latest('fecha_inicio')
            ->first();

        if ($esDirectivo) {
            // 1. Tarjetas de Indicadores Globales
            $totalProfesores = Profesor::count();
            $totalEstudiantes = Persona::count();
            $totalEmpresas = Empresa::count();
            $totalSecciones = Seccion::activasParaAsignacion()->count();

            // 2. Gráfico: Estudiantes por PNF (Excluyendo PNFs con 0 estudiantes)
            $metricasPnf = DB::table('pnfs')
                ->select('pnfs.nombre_pnf', DB::raw('COUNT(DISTINCT inscripciones_secciones.id_personas) as total_estudiantes'))
                ->leftJoin('titulacion_personas', function ($join) {
                    $join->on('pnfs.id_pnf', '=', 'titulacion_personas.id_pnf')
                         ->whereNull('titulacion_personas.deleted_at');
                })
                ->leftJoin('inscripciones_secciones', function ($join) {
                    $join->on('titulacion_personas.id_personas', '=', 'inscripciones_secciones.id_personas')
                         ->whereIn('inscripciones_secciones.estatus_inscripcion', ['Activo', 'ACTIVO', 'activa', 'Activa'])
                         ->whereNull('inscripciones_secciones.deleted_at');
                })
                ->when($periodoActual, function ($query) use ($periodoActual) {
                    return $query->leftJoin('secciones', 'inscripciones_secciones.id_seccion', '=', 'secciones.id_seccion')
                                 ->where(function ($q) use ($periodoActual) {
                                     $q->where('secciones.id_periodo', $periodoActual->id_periodo)
                                       ->orWhereNull('secciones.id_periodo');
                                 });
                })
                ->where('pnfs.vigencia_pnf', true)
                ->groupBy('pnfs.id_pnf', 'pnfs.nombre_pnf')
                ->having('total_estudiantes', '>', 0) // <-- Oculta PNFs sin estudiantes
                ->orderByDesc('total_estudiantes')
                ->get()
                ->map(function ($pnf) {
                    return [
                        'nombre_pnf'        => $pnf->nombre_pnf,
                        'total_estudiantes' => (int) $pnf->total_estudiantes,
                    ];
                })
                ->values()
                ->toArray();

            // Fallback por expediente si no hay inscripciones activas registradas en secciones
            if (empty($metricasPnf)) {
                $metricasPnf = DB::table('pnfs')
                    ->select('pnfs.nombre_pnf', DB::raw('COUNT(DISTINCT titulacion_personas.id_personas) as total_estudiantes'))
                    ->leftJoin('titulacion_personas', function ($join) {
                        $join->on('pnfs.id_pnf', '=', 'titulacion_personas.id_pnf')
                             ->whereNull('titulacion_personas.deleted_at');
                    })
                    ->where('pnfs.vigencia_pnf', true)
                    ->groupBy('pnfs.id_pnf', 'pnfs.nombre_pnf')
                    ->having('total_estudiantes', '>', 0) // <-- Oculta PNFs sin estudiantes
                    ->orderByDesc('total_estudiantes')
                    ->get()
                    ->map(function ($pnf) {
                        return [
                            'nombre_pnf'        => $pnf->nombre_pnf,
                            'total_estudiantes' => (int) $pnf->total_estudiantes,
                        ];
                    })
                    ->values()
                    ->toArray();
            }

            // 3. Distribución por Empresas (Solo empresas con 1 o más estudiantes)
            $totalAlumnosEmpresa = DB::table('empresa_personas')->count('id_personas');

            $metricasEmpresas = DB::table('empresas')
                ->select(
                    'empresas.nombre_empresa',
                    DB::raw('COUNT(empresa_personas.id_personas) as total_alumnos')
                )
                ->join('empresa_personas', 'empresas.id_empresa', '=', 'empresa_personas.id_empresa')
                ->groupBy('empresas.id_empresa', 'empresas.nombre_empresa')
                ->having('total_alumnos', '>', 0)
                ->orderByDesc('total_alumnos')
                ->get()
                ->map(function ($empresa) use ($totalAlumnosEmpresa) {
                    $porcentaje = $totalAlumnosEmpresa > 0
                        ? round(($empresa->total_alumnos / $totalAlumnosEmpresa) * 100, 1)
                        : 0;

                    return [
                        'nombre_empresa' => $empresa->nombre_empresa,
                        'total_alumnos'  => (int) $empresa->total_alumnos,
                        'porcentaje'     => $porcentaje,
                    ];
                })
                ->values()
                ->toArray();

            // 4. Datos de Asistencia Global procesados mediante el AttendanceHierarchyService
            $jerarquiaGlobal = $hierarchyService->obtenerJerarquiaPnfEmpresas(request());

            // 4.1. Porcentaje de Participación de Asistencia por PNF
            $asistenciaPnfChart = [];
            foreach ($jerarquiaGlobal as $itemPnf) {
                $meses = $itemPnf['porcentaje_participacion_meses'] ?? [];
                $mesesValidos = array_filter($meses, fn($val) => !is_null($val));
                $promedioPnf = count($mesesValidos) > 0 ? array_sum($mesesValidos) / count($mesesValidos) : 0;
                
                if ($itemPnf['total_estudiantes'] > 0 && $promedioPnf > 0) {
                    $asistenciaPnfChart[] = [
                        'nombre_pnf' => $itemPnf['nombre_pnf'],
                        'porcentaje' => round($promedioPnf, 2)
                    ];
                }
            }

            // 4.2. Porcentaje de Participación de Asistencia por Empresa
            $empresasAsistenciaMap = [];
            foreach ($jerarquiaGlobal as $itemPnf) {
                foreach ($itemPnf['empresas'] as $emp) {
                    $nombreEmp = $emp['nombre_empresa'];
                    $mesesEmp = array_filter($emp['porcentaje_meses'] ?? [], fn($v) => !is_null($v));
                    $promedioEmp = count($mesesEmp) > 0 ? array_sum($mesesEmp) / count($mesesEmp) : 0;

                    if ($promedioEmp > 0) {
                        if (!isset($empresasAsistenciaMap[$nombreEmp])) {
                            $empresasAsistenciaMap[$nombreEmp] = [];
                        }
                        $empresasAsistenciaMap[$nombreEmp][] = $promedioEmp;
                    }
                }
            }

            $asistenciaEmpresaChart = [];
            foreach ($empresasAsistenciaMap as $nombreEmp => $promedios) {
                $asistenciaEmpresaChart[] = [
                    'nombre_empresa' => $nombreEmp,
                    'porcentaje'     => round(array_sum($promedios) / count($promedios), 2)
                ];
            }
            usort($asistenciaEmpresaChart, fn($a, $b) => $b['porcentaje'] <=> $a['porcentaje']);

            return view('index', compact(
                'periodoActual',
                'totalProfesores',
                'totalEstudiantes',
                'totalEmpresas',
                'totalSecciones',
                'metricasPnf',
                'metricasEmpresas',
                'asistenciaPnfChart',
                'asistenciaEmpresaChart'
            ));
        }

        // VISTA OPERATIVA (PROFESOR)
        $profesor = $user->profesor;
        $idProfesor = $profesor ? $profesor->id_profesor : null;

        $misSecciones = $idProfesor
            ? Seccion::porProfesor($idProfesor)->count()
            : 0;

        $misAlumnos = $idProfesor
            ? Persona::whereHas('inscripcionesSecciones.seccion.profesores', function ($q) use ($idProfesor) {
                $q->where('profesores.id_profesor', $idProfesor);
            })->count()
            : 0;

        $misClasesPendientes = $idProfesor
            ? Sesion::where(function ($query) use ($idProfesor) {
                    // Opción A: Sesiones donde el profesor es el creador directo
                    $query->where('id_profesor', $idProfesor)
                          // Opción B: O sesiones que pertenecen a secciones donde este profesor imparte clases
                          ->orWhereHas('seccion.profesores', function ($q) use ($idProfesor) {
                              $q->where('profesores.id_profesor', $idProfesor);
                          });
                })
                ->whereHas('seccion')
                ->with('seccion')
                ->whereDoesntHave('asistencias') // Clases que no tienen asistencia registrada
                // ->where('fecha_sesion', '<=', now()->toDateString()) // <--- COMENTADO TEMPORALMENTE PARA PROBAR SI SON FECHAS FUTURAS
                ->orderBy('fecha_sesion', 'asc')
                ->get()
            : collect();

        return view('index', compact(
            'periodoActual',
            'misSecciones',
            'misAlumnos',
            'misClasesPendientes'
        ));
    }
}