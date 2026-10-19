<?php

// namespace App\Http\Controllers;

// use Illuminate\Http\Request;
// use App\Models\User;

// class AdminController extends Controller
// {
//     public function __construct()
//     {
//         $this->middleware('auth');
//     }

//     public function index()
//     {
//         // Obtenemos solo los usuarios necesarios
//         $usuarios = User::all();

//         // Retornamos la vista index sin las variables obsoletas
//         return view('index', compact('usuarios'));
//     }
// }

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Profesor;
use App\Models\Persona;
use App\Models\Empresa;
use App\Models\Seccion;
use App\Models\Sesion;
use App\Models\PeriodoAcademico;
use App\Models\InscripcionSeccion;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $esDirectivo = $user->isAdmin() || $user->isCoordinador();

        // =====================================================================
        // VARIABLES COMUNES / INICIALIZACIÓN
        // =====================================================================
        $periodoActual              = null;
        $totalProfesores            = 0;
        $totalEstudiantes           = 0;
        $totalEmpresas              = 0;
        $totalSecciones             = 0;
        $sesionesPendientesGlobales = collect();
        $metricasEmpresas           = collect();

        $misSecciones               = 0;
        $misAlumnos                 = 0;
        $misClasesPendientes        = collect();

        // =====================================================================
        // RAMA 1: DASHBOARD DIRECTIVO (ADMINISTRADOR / COORDINADOR)
        // =====================================================================
        if ($esDirectivo) {

            // 1. Período Académico Activo
            $periodoActual = PeriodoAcademico::with('cohorte')
                ->whereIn('estatus_periodo', ['Activo', 'Activa', 'activo', 'activa'])
                ->latest('fecha_inicio')
                ->first();

            if ($periodoActual) {
                $numCohorte = $periodoActual->cohorte->numero_cohorte ?? 'S/C';
                $fInicio    = $periodoActual->fecha_inicio ? $periodoActual->fecha_inicio->format('Y') : '';
                $fFin       = $periodoActual->fecha_fin ? $periodoActual->fecha_fin->format('Y') : '';
                $lapso      = ($fInicio && $fFin) ? "({$fInicio}-{$fFin})" : '';
                
                // Sintetizamos el atributo esperado por la vista:
                $periodoActual->nombre_periodo = "Cohorte {$numCohorte} {$lapso}";
            }

            // 2. Tarjetas de Métricas Globales
            $totalProfesores = Profesor::count();

            // Total de estudiantes activos (matriculados en secciones activas)
            $totalEstudiantes = Persona::whereHas('inscripcionesSecciones', function ($q) {
                $q->whereIn('estatus_inscripcion', ['Activo', 'Activa', 'activo', 'activa']);
            })->count();

            if ($totalEstudiantes === 0) {
                $totalEstudiantes = Persona::count();
            }

            $totalEmpresas = Empresa::count();

            // Total de secciones activas en el sistema
            $totalSecciones = Seccion::activas()->count();
            if ($totalSecciones === 0) {
                $totalSecciones = Seccion::count();
            }

            // 3. Radar de Cumplimiento (Sesiones pasadas o de hoy sin asistencia registrada)
            $sesionesPendientesGlobales = Sesion::with(['seccion', 'profesor.user'])
                ->whereDate('fecha_sesion', '<=', now()->toDateString())
                ->doesntHave('asistencias')
                ->orderBy('fecha_sesion', 'desc')
                ->take(10)
                ->get();

            // 4. Matrícula por Empresa (CVG) con cálculo de porcentajes para barras de progreso
            $totalConEmpresa = DB::table('empresa_personas')
                ->whereNull('deleted_at')
                ->distinct('id_personas')
                ->count('id_personas');

            $metricasEmpresas = DB::table('empresas')
                ->leftJoin('empresa_personas', function ($join) {
                    $join->on('empresas.id_empresa', '=', 'empresa_personas.id_empresa')
                         ->whereNull('empresa_personas.deleted_at');
                })
                ->select(
                    'empresas.nombre_empresa',
                    DB::raw('COUNT(DISTINCT empresa_personas.id_personas) as total_alumnos')
                )
                ->groupBy('empresas.id_empresa', 'empresas.nombre_empresa')
                ->orderByDesc('total_alumnos')
                ->take(6)
                ->get()
                ->map(function ($item) use ($totalConEmpresa) {
                    $item->porcentaje = $totalConEmpresa > 0
                        ? (int) round(($item->total_alumnos / $totalConEmpresa) * 100)
                        : 0;
                    return $item;
                });

        // =====================================================================
        // RAMA 2: DASHBOARD OPERATIVO (PROFESOR)
        // =====================================================================
        } else {
            
            $profesor = $user->profesor;

            if ($profesor) {
                // IDs de las secciones que el docente tiene asignadas en la tabla pivote
                $seccionIds = $profesor->secciones()->pluck('secciones.id_seccion');

                // 1. Número de secciones asignadas
                $misSecciones = $seccionIds->count();

                // 2. Estudiantes activos únicos en las secciones del profesor
                $misAlumnos = InscripcionSeccion::whereIn('id_seccion', $seccionIds)
                    ->whereIn('estatus_inscripcion', ['Activo', 'Activa', 'activo', 'activa'])
                    ->distinct('id_personas')
                    ->count('id_personas');

                // 3. Clases pasadas o de hoy en sus secciones que aún no tienen asistencia registrada
                $misClasesPendientes = Sesion::with('seccion')
                    ->whereIn('id_seccion', $seccionIds)
                    ->whereDate('fecha_sesion', '<=', now()->toDateString())
                    ->doesntHave('asistencias')
                    ->orderBy('fecha_sesion', 'desc')
                    ->get();
            }
        }

        // =====================================================================
        // RETORNO A LA VISTA CON TODAS LAS VARIABLES VINCULADAS
        // =====================================================================
        return view('index', compact(
            'periodoActual',
            'totalProfesores',
            'totalEstudiantes',
            'totalEmpresas',
            'totalSecciones',
            'sesionesPendientesGlobales',
            'metricasEmpresas',
            'misSecciones',
            'misAlumnos',
            'misClasesPendientes'
        ));
    }
}