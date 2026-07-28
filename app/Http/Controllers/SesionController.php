<?php

namespace App\Http\Controllers;

use App\Models\Sesion;
use App\Models\GrupoAcademico;
use App\Models\Profesor;
use App\Models\InscripcionCohorte; // <--- 1. IMPORTACIÓN CORRECTA DEL MODELO
use App\Models\Asistencia;
use App\Http\Requests\StoreSesionRequest;
use Illuminate\Support\Facades\Auth;

class SesionController extends Controller
{
    /**
     * Lista el historial de clases impartidas (Solo las del profesor autenticado).
     */
    public function index()
    {
        $user = Auth::user();
        $profesor = Profesor::where('id_users', $user->id_users)->firstOrFail();

        // SEGURIDAD: Traer solo las sesiones donde el profesor dictó la clase
        $sesiones = Sesion::with(['grupo.cohorte', 'grupo.pnf'])
            ->where('id_profesor', $profesor->id_profesor)
            ->orderBy('fecha_sesion', 'desc')
            ->paginate(15);

        return view('sesiones.index', compact('sesiones'));
    }

    public function create()
    {
        $user = Auth::user();
        $profesor = Profesor::where('id_users', $user->id_users)->firstOrFail();

        // SEGURIDAD: Cargar ÚNICAMENTE los grupos que este profesor tiene asignados en la pivote
        $grupos = $profesor->grupos()
            ->with(['cohorte', 'pnf'])
            ->where('estatus_grupo', 'Activo')
            ->get();

        return view('sesiones.create', compact('grupos', 'profesor'));
    }

    public function store(StoreSesionRequest $request)
    {
        $data = $request->validated();
        $user = Auth::user();
        $profesor = Profesor::where('id_users', $user->id_users)->firstOrFail();

        // RESTRICCIÓN DE SEGURIDAD FINAL: Validar que el id_grupo enviado pertenezca a este docente
        $tieneAcceso = $profesor->grupos()->where('profesor_grupo.id_grupo', $data['id_grupo'])->exists();

        if (!$tieneAcceso) {
            return back()->withErrors([
                'id_grupo' => 'No tienes autorización para aperturar sesiones ni tomar asistencia en este grupo académico.'
            ])->withInput();
        }

        // Inyectamos el ID del profesor de manera segura desde el servidor
        $data['id_profesor'] = $profesor->id_profesor;
        
        $sesion = Sesion::create($data);

        // Redirige a la vista de la sesión para proceder a tomar la lista de asistencia
        return redirect()->route('sesiones.show', $sesion->id_sesiones)
            ->with('success', 'Sesión académica aperturada correctamente. Proceda a tomar la asistencia.');
    }

    /**
     * Muestra la sesión específica para ver o corregir la asistencia (FASE 4).
     */
   public function show(Sesion $sesion)
    {
        $user = Auth::user();
        $profesor = Profesor::where('id_users', $user->id_users)->firstOrFail();

        // SEGURIDAD: Validar que el profesor no intente ver la clase de otro colega por URL
        if ($sesion->id_profesor !== $profesor->id_profesor) {
            abort(403, 'Acceso denegado: No tienes permiso para visualizar esta sesión.');
        }

        // Cargamos las relaciones de la sesión (Grupo, PNF, Cohorte)
        $sesion->load(['grupo.cohorte', 'grupo.pnf']);

        // 1. Obtenemos la lista de estudiantes inscritos en el grupo
        $inscripciones = InscripcionCohorte::with('persona')
            ->where('id_grupo', $sesion->id_grupo)
            ->get()
            ->sortBy(function($inscripcion) {
                return $inscripcion->persona->last_name_users ?? '';
            });

        // 2. CORREGIDO: Buscamos las asistencias previas usando 'id_sesiones' 
        // y hacemos el pluck usando 'id_inscripcion_cohortes' como índice.
        $asistenciasRegistradas = Asistencia::where('id_sesiones', $sesion->id_sesiones)
            ->pluck('estado_asistencia', 'id_inscripcion_cohortes');

        // Retornamos a la vista pasando la sesión, los alumnos y las asistencias previas
        return view('sesiones.show', compact('sesion', 'inscripciones', 'asistenciasRegistradas'));
    }
}