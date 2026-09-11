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

        // Control perimetral de seguridad para profesores
        if (!$user->isAdmin() && !$user->isCoordinador()) {
            $profesorId = $user->profesor ? $user->profesor->id_profesor : -1;
            $tieneAcceso = $seccion->profesores()->where('profesor_seccion.id_profesor', $profesorId)->exists();

            if (!$tieneAcceso) {
                abort(403, 'No posee autorizacion para ver las sesiones de esta seccion.');
            }
        }

        // Interceptor AJAX para que Yajra reciba el JSON de la tabla de sesiones
        if (request()->ajax() || request()->wantsJson()) {
            return $dataTable->withIdSeccion($seccion->id_seccion)->ajax();
        }

        $seccion->load(['periodoAcademico.cohorte', 'pnf', 'profesores.user']);

        return $dataTable->withIdSeccion($seccion->id_seccion)->render('sesiones.por_seccion', compact('seccion'));
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

        $data = $request->validated();
        $user = Auth::user();

        if ($user->isProfesor()) {
            $profesorValido = Profesor::where('id_profesor', $data['id_profesor'])
                ->whereHas('secciones', function ($query) use ($data) {
                    $query->where('secciones.id_seccion', $data['id_seccion']);
                })->exists();

            if (!$profesorValido) {
                return back()->withErrors([
                    'id_profesor' => 'El profesor seleccionado no está asignado como docente de esta sección académica.'
                ])->withInput();
            }
        }

        Sesion::create($data);

        return redirect()->route('clases.secciones.sesiones', $data['id_seccion'])
            ->with('success', 'Sesión académica programada y registrada correctamente.');
    }

    public function show(Sesion $sesion, AsistenciaSesionDataTable $dataTable)
    {
        Gate::authorize('view', $sesion);

        $sesion->load(['seccion.periodoAcademico.cohorte', 'seccion.pnf', 'profesor.user']);

        $asistenciasRegistradas = Asistencia::where('id_sesiones', $sesion->id_sesiones)
            ->pluck('estado_asistencia', 'id_inscripcion_seccion')
            ->toArray();

        $puedeEditar = Auth::user()->can('update', $sesion);

        // Interceptor AJAX para Yajra DataTables
        if (request()->ajax() || request()->wantsJson()) {
            return $dataTable->withSesionData(
                $sesion->id_sesiones,
                $sesion->id_seccion,
                $asistenciasRegistradas,
                $puedeEditar
            )->ajax();
        }

        $totalInscritos = InscripcionSeccion::where('id_seccion', $sesion->id_seccion)
            ->where('estatus_inscripcion', 'Activo')
            ->count();

        return $dataTable->withSesionData(
            $sesion->id_sesiones,
            $sesion->id_seccion,
            $asistenciasRegistradas,
            $puedeEditar
        )->render('sesiones.show', compact('sesion', 'totalInscritos', 'puedeEditar'));
    }

    public function destroy(Sesion $sesion)
    {
        Gate::authorize('delete', $sesion);

        $idSeccion = $sesion->id_seccion;
        $sesion->delete();

        return redirect()->route('clases.secciones.sesiones', $idSeccion)
            ->with('success', 'Sesión eliminada correctamente del calendario.');
    }
}