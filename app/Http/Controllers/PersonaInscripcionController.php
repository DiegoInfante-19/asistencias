<?php

namespace App\Http\Controllers;

use App\Models\Persona;
use App\Models\InscripcionCohorte;
use App\Http\Requests\StoreInscripcionCohorteRequest;
use Illuminate\Support\Facades\Log;

class PersonaInscripcionController extends Controller
{
    /**
     * Almacena una nueva inscripción a una cohorte para la persona.
     */
    public function store(StoreInscripcionCohorteRequest $request, Persona $persona)
    {
        try {
            // Asumiendo que en Persona.php tienes la relación definida como:
            // public function inscripciones() { return $this->hasMany(InscripcionCohorte::class, 'id_personas', 'id_personas'); }
            
            $persona->inscripciones()->create($request->validated());

            return redirect()->back()->with('success', 'Estudiante inscrito en la cohorte exitosamente.');
            
        } catch (\Exception $e) {
            Log::error('Error inscribiendo al estudiante: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Ocurrió un error al intentar registrar la inscripción.');
        }
    }

    /**
     * Elimina una inscripción (Escenario de anulación o retiro).
     */
    public function destroy(Persona $persona, InscripcionCohorte $inscripcion)
    {
        try {
            // Medida de seguridad Senior:
            // Validamos que esta inscripción realmente le pertenezca a la persona del contexto.
            if ($inscripcion->id_personas !== $persona->id_personas) {
                return redirect()->back()->with('error', 'No tienes permiso para eliminar esta inscripción.');
            }

            // Recordando el inicio de nuestra arquitectura: 
            // InscripcionCohorte.php usa SoftDeletes. 
            // Así que esto mantiene el registro histórico por auditoría (deleted_at).
            $inscripcion->delete();

            return redirect()->back()->with('success', 'Inscripción anulada correctamente.');
            
        } catch (\Exception $e) {
            Log::error('Error eliminando inscripción: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Ocurrió un error al intentar eliminar la inscripción.');
        }
    }
}