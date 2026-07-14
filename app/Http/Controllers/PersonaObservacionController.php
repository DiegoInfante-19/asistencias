<?php

namespace App\Http\Controllers;

use App\Models\Persona;
use App\Http\Requests\StoreObservacionPersonaRequest;
use Illuminate\Support\Facades\Log;

class PersonaObservacionController extends Controller
{
    /**
     * Almacena o actualiza la observación única asociada a la persona.
     */
    public function store(StoreObservacionPersonaRequest $request, Persona $persona)
    {
        try {
            // =================================================================
            // LA REGLA 1 A 1 EN ACCIÓN
            // =================================================================
            // updateOrCreate() recibe dos arrays:
            // 1. Las condiciones para buscar el registro (el ID de la persona).
            // 2. Los datos que se van a guardar o actualizar.
            // 
            // Si el estudiante no tiene observación, Laravel hace un INSERT.
            // Si ya tiene una, Laravel hace un UPDATE sobre ese mismo registro.
            
            $persona->observacion()->updateOrCreate(
                ['id_personas' => $persona->id_personas], 
                $request->validated()
            );

            return redirect()->back()->with('success', 'Observación guardada exitosamente.');
            
        } catch (\Exception $e) {
            Log::error('Error guardando la observación: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Ocurrió un error al guardar la observación.');
        }
    }
}
