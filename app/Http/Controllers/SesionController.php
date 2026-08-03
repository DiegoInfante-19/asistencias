<?php

namespace App\Http\Controllers;

use App\Models\Sesion;
use App\Models\GrupoAcademico;
use App\Models\Profesor;
use App\Models\InscripcionCohorte; 
use App\Models\Asistencia;
use App\Models\PeriodoReceso;
use App\Http\Requests\StoreSesionRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class SesionController extends Controller
{
    public function index()
    {
        Gate::authorize('viewAny', Sesion::class);

        /** @var \App\Models\User $user */
        $user = Auth::user();

        if ($user->isAdmin() || $user->isCoordinador()) {
            // Los superiores ven TODAS las clases de todos los profesores
            $sesiones = Sesion::with(['grupo.cohorte', 'grupo.pnf'])
                ->orderBy('fecha_sesion', 'desc')
                ->paginate(15);
        } else {
            // PREVENCIÓN DE CRASH: Si el profesor es nuevo y aún no tiene carga asignada
            if (!$user->profesor) {
                // Retornamos una consulta vacía paginada de forma segura
                $sesiones = Sesion::where('id_profesor', -1)->paginate(15);
            } else {
                // El profesor solo ve sus propias clases
                $sesiones = Sesion::with(['grupo.cohorte', 'grupo.pnf'])
                    ->where('id_profesor', $user->profesor->id_profesor)
                    ->orderBy('fecha_sesion', 'desc')
                    ->paginate(15);
            }
        }

        return view('sesiones.index', compact('sesiones'));
    }

    public function create()
    {
        Gate::authorize('create', Sesion::class);
        
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $profesor = $user->profesor;

        // PREVENCIÓN DE CRASH (Admin/Coordinador audita pero no dicta clases, o Profesor sin asignar)
        if (!$profesor) {
            return redirect()->route('sesiones.index')
                ->with('error', 'Acceso denegado: Debes tener una carga académica asignada para poder aperturar clases.');
        }

        $grupos = $profesor->grupos()
            ->with(['cohorte', 'pnf'])
            ->where('estatus_grupo', 'Activo')
            ->get();

        $periodosRecesos = PeriodoReceso::where('suspension_actividades', 1)
            ->select('fecha_inicio_periodo_receso', 'fecha_fin_periodo_receso')
            ->get();

        return view('sesiones.create', compact('grupos', 'profesor', 'periodosRecesos'));
    }

    public function store(StoreSesionRequest $request)
    {
        Gate::authorize('create', Sesion::class);

        $data = $request->validated();
        
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $profesor = $user->profesor;

        // PREVENCIÓN DE CRASH: Doble validación al guardar
        if (!$profesor) {
            abort(403, 'Acceso denegado: No posees un perfil docente válido para registrar esta acción.');
        }

        $tieneAcceso = $profesor->grupos()->where('profesor_grupo.id_grupo', $data['id_grupo'])->exists();

        if (!$tieneAcceso) {
            return back()->withErrors([
                'id_grupo' => 'No tienes autorización para aperturar sesiones ni tomar asistencia en este grupo académico.'
            ])->withInput();
        }

        $data['id_profesor'] = $profesor->id_profesor;
        $sesion = Sesion::create($data);

        return redirect()->route('sesiones.show', $sesion->id_sesiones)
            ->with('success', 'Sesión académica aperturada correctamente. Proceda a tomar la asistencia.');
    }

    public function show(Sesion $sesion)
    {
        Gate::authorize('view', $sesion);

        $sesion->load(['grupo.cohorte', 'grupo.pnf']);

        $inscripciones = InscripcionCohorte::with('persona')
            ->where('id_grupo', $sesion->id_grupo)
            ->get()
            ->sortBy(function($inscripcion) {
                return $inscripcion->persona->last_name_users ?? '';
            });

        $asistenciasRegistradas = Asistencia::where('id_sesiones', $sesion->id_sesiones)
            ->pluck('estado_asistencia', 'id_inscripcion_cohortes');

        return view('sesiones.show', compact('sesion', 'inscripciones', 'asistenciasRegistradas'));
    }
}