<?php

namespace App\Http\Controllers;

use App\Models\Sesion;
use App\Models\Seccion;
use App\Models\Profesor;
use App\Models\InscripcionSeccion; 
use App\Models\Asistencia;
use App\Models\PeriodoReceso;
use App\Http\Requests\StoreSesionRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Http\Request;
use Carbon\Carbon;

class SesionController extends Controller
{
    public function index()
    {
        Gate::authorize('viewAny', Sesion::class);

        /** @var \App\Models\User $user */
        $user = Auth::user();

        if ($user->isAdmin() || $user->isCoordinador()) {
            $sesiones = Sesion::with(['seccion.periodoAcademico.cohorte', 'seccion.pnf', 'profesor.user'])
                ->orderBy('fecha_sesion', 'desc')
                ->paginate(15);
        } else {
            if (!$user->profesor) {
                $sesiones = Sesion::where('id_profesor', -1)->paginate(15);
            } else {
                $sesiones = Sesion::with(['seccion.periodoAcademico.cohorte', 'seccion.pnf'])
                    ->where('id_profesor', $user->profesor->id_profesor)
                    ->whereDate('fecha_sesion', Carbon::today())
                    ->orderBy('fecha_sesion', 'desc')
                    ->paginate(15);
            }
        }

        return view('sesiones.index', compact('sesiones'));
    }

    public function create()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        if (!$user->isAdmin() && !$user->isCoordinador()) {
            abort(403, 'Acceso denegado: Solo el Administrador puede programar y aperturar sesiones de clase.');
        }

        $secciones = Seccion::with(['periodoAcademico.cohorte', 'pnf', 'profesores.user'])
            ->where('estatus_seccion', 'Activa')
            ->get();

        $periodosRecesos = PeriodoReceso::where('suspension_actividades', 1)
            ->select('fecha_inicio_periodo_receso', 'fecha_fin_periodo_receso')
            ->get();

        return view('sesiones.create', compact('secciones', 'periodosRecesos'));
    }

    public function store(StoreSesionRequest $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        if (!$user->isAdmin() && !$user->isCoordinador()) {
            abort(403, 'Acceso denegado: Solo el Administrador puede registrar nuevas sesiones.');
        }

        $data = $request->validated();

        $profesorValido = Profesor::where('id_profesor', $data['id_profesor'])
            ->whereHas('secciones', function ($query) use ($data) {
                $query->where('secciones.id_seccion', $data['id_seccion']);
            })->exists();

        if (!$profesorValido) {
            return back()->withErrors([
                'id_profesor' => 'El profesor seleccionado no está asignado como docente de esta sección académica.'
            ])->withInput();
        }

        $sesion = Sesion::create($data);

        return redirect()->route('sesiones.show', $sesion->id_sesiones)
            ->with('success', 'Sesión académica programada y aperturada correctamente.');
    }

    public function show(Sesion $sesion)
    {
        Gate::authorize('view', $sesion);

        $sesion->load(['seccion.periodoAcademico.cohorte', 'seccion.pnf', 'profesor.user']);

        // FASE 6: Carga de los estudiantes que pertenecen a la sección mixta
        $inscripciones = InscripcionSeccion::with('persona')
            ->where('id_seccion', $sesion->id_seccion)
            ->where('estatus_inscripcion', 'Activo')
            ->get()
            ->sortBy(function($inscripcion) {
                // Ajustado para ordenar usando la propiedad correcta de Persona
                return $inscripcion->persona->nombre_completo ?? $inscripcion->persona->cedula_personas;
            });

        // FASE 6: Llave foránea actualizada
        $asistenciasRegistradas = Asistencia::where('id_sesiones', $sesion->id_sesiones)
            ->pluck('estado_asistencia', 'id_inscripcion_seccion');

        return view('sesiones.show', compact('sesion', 'inscripciones', 'asistenciasRegistradas'));
    }
}