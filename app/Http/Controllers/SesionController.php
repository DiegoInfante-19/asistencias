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

class SesionController extends Controller
{
    public function index()
    {
        Gate::authorize('viewAny', Sesion::class);

        /** @var \App\Models\User $user */
        $user = Auth::user();

        if ($user->isAdmin() || $user->isCoordinador()) {
            // Los superiores ven TODAS las sesiones de clases de todas las secciones
            $sesiones = Sesion::with(['seccion.periodoAcademico.cohorte', 'seccion.pnf', 'profesor.user'])
                ->orderBy('fecha_sesion', 'desc')
                ->paginate(15);
        } else {
            if (!$user->profesor) {
                $sesiones = Sesion::where('id_profesor', -1)->paginate(15);
            } else {
                // El profesor ve solo las sesiones de sus secciones asignadas
                $sesiones = Sesion::with(['seccion.periodoAcademico.cohorte', 'seccion.pnf'])
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

        if (!$profesor) {
            return redirect()->route('sesiones.index')
                ->with('error', 'Acceso denegado: Debes tener una carga académica asignada para poder aperturar clases.');
        }

        // Secciones asignadas al profesor mediante la relación N:M (pivot profesor_seccion)
        $secciones = $profesor->secciones()
            ->with(['periodoAcademico.cohorte', 'pnf'])
            ->where('estatus_seccion', 'Activa')
            ->get();

        $periodosRecesos = PeriodoReceso::where('suspension_actividades', 1)
            ->select('fecha_inicio_periodo_receso', 'fecha_fin_periodo_receso')
            ->get();

        return view('sesiones.create', compact('secciones', 'profesor', 'periodosRecesos'));
    }

    public function store(StoreSesionRequest $request)
    {
        Gate::authorize('create', Sesion::class);

        $data = $request->validated();
        
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $profesor = $user->profesor;

        if (!$profesor) {
            abort(403, 'Acceso denegado: No posees un perfil docente válido para registrar esta acción.');
        }

        // Verificamos que el profesor tenga asignada esta sección en la pivote N:M
        $tieneAcceso = $profesor->secciones()->where('profesor_seccion.id_seccion', $data['id_seccion'])->exists();

        if (!$tieneAcceso) {
            return back()->withErrors([
                'id_seccion' => 'No tienes autorización para aperturar sesiones ni tomar asistencia en esta sección académica.'
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

        $sesion->load(['seccion.periodoAcademico.cohorte', 'seccion.pnf', 'profesor.user']);

        // Estudiantes inscritos en la sección (pueden pertenecer a N cohortes distintas)
        $inscripciones = InscripcionSeccion::with('persona')
            ->where('id_seccion', $sesion->id_seccion)
            ->where('estatus_inscripcion', 'Activo')
            ->get()
            ->sortBy(function($inscripcion) {
                return $inscripcion->persona->last_name_users ?? '';
            });

        $asistenciasRegistradas = Asistencia::where('id_sesiones', $sesion->id_sesiones)
            ->pluck('estado_asistencia', 'id_inscripcion_seccion'); // Llave actualizada a la nueva tabla

        return view('sesiones.show', compact('sesion', 'inscripciones', 'asistenciasRegistradas'));
    }
}