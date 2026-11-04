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

class DashboardController extends Controller
{
    public function index()
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

            // 2. Gráfico de Pastel: Distribución de Estudiantes por PNF
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

            // Fallback por expediente si no hay inscripciones activas
            $totalInscritosCount = array_sum(array_column($metricasPnf, 'total_estudiantes'));
            if ($totalInscritosCount === 0) {
                $metricasPnf = DB::table('pnfs')
                    ->select('pnfs.nombre_pnf', DB::raw('COUNT(DISTINCT titulacion_personas.id_personas) as total_estudiantes'))
                    ->leftJoin('titulacion_personas', function ($join) {
                        $join->on('pnfs.id_pnf', '=', 'titulacion_personas.id_pnf')
                             ->whereNull('titulacion_personas.deleted_at');
                    })
                    ->where('pnfs.vigencia_pnf', true)
                    ->groupBy('pnfs.id_pnf', 'pnfs.nombre_pnf')
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

                    return (object) [
                        'nombre_empresa' => $empresa->nombre_empresa,
                        'total_alumnos'  => (int) $empresa->total_alumnos,
                        'porcentaje'     => $porcentaje,
                    ];
                });

            return view('index', compact(
                'periodoActual',
                'totalProfesores',
                'totalEstudiantes',
                'totalEmpresas',
                'totalSecciones',
                'metricasPnf',
                'metricasEmpresas'
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
            ? Sesion::where('id_profesor', $idProfesor)
                ->doesntHave('asistencias')
                ->with('seccion')
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