<?php

namespace App\Http\Controllers;

use App\Models\Sesion;
use App\Models\GrupoAcademico;
use App\Models\Profesor;
use App\Http\Requests\StoreSesionRequest;
use Illuminate\Support\Facades\Auth;

class SesionController extends Controller
{
    /**
     * Muestra el formulario para registrar una nueva sesión de clase.
     */
    public function create()
    {
        $user = Auth::user();
        
        // Obtener el perfil de profesor asociado al usuario autenticado
        $profesor = Profesor::where('id_users', $user->id_users)->firstOrFail();

        // SEGURIDAD: Cargar ÚNICAMENTE los grupos que este profesor tiene asignados en la tabla pivote
        $grupos = $profesor->grupos()
            ->with(['cohorte', 'pnf'])
            ->where('estatus_grupo', 'Activo')
            ->get();

        return view('sesiones.create', compact('grupos', 'profesor'));
    }

    /**
     * Guarda la sesión y verifica la autorización sobre el grupo.
     */
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

        // Asignar el id_profesor autenticado y registrar la sesión
        $data['id_profesor'] = $profesor->id_profesor;
        $sesion = Sesion::create($data);

        return redirect()->route('sesiones.show', $sesion->id_sesiones)
            ->with('success', 'Sesión académica aperturada correctamente.');
    }
}