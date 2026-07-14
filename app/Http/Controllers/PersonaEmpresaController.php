<?php

namespace App\Http\Controllers;

use App\Models\Persona;
use App\Models\EmpresaPersona;
use App\Http\Requests\StoreEmpresaPersonaRequest;
use Illuminate\Support\Facades\Log;

class PersonaEmpresaController extends Controller
{
    /**
     * Almacena o actualiza el perfil laboral único de la persona.
     */
    public function store(StoreEmpresaPersonaRequest $request, Persona $persona)
    {
        try {
            // =================================================================
            // LA REGLA 1 A 1 (Actualizar o Crear)
            // =================================================================
            // Si el estudiante cambia de trabajo o lo ascienden (cambia de cargo),
            // el sistema simplemente sobrescribirá el registro actual.
            
            $persona->empresaPersona()->updateOrCreate(
                ['id_personas' => $persona->id_personas],
                $request->validated()
            );

            return redirect()->back()->with('success', 'Perfil laboral guardado exitosamente.');
            
        } catch (\Exception $e) {
            Log::error('Error guardando perfil laboral: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Ocurrió un error al guardar el perfil laboral.');
        }
    }

    /**
     * Elimina el perfil laboral (Escenario de desempleo).
     */
    public function destroy(Persona $persona, EmpresaPersona $empresa)
    {
        try {
            // Medida de seguridad Senior: 
            // Verificar que este registro laboral realmente pertenece al estudiante actual.
            if ($empresa->id_personas !== $persona->id_personas) {
                return redirect()->back()->with('error', 'No tienes permiso para eliminar este registro.');
            }

            // Aquí Eloquent aplicará el SoftDeletes que configuramos en el modelo EmpresaPersona,
            // manteniendo el historial en la base de datos (deleted_at).
            $empresa->delete();

            return redirect()->back()->with('success', 'Perfil laboral eliminado correctamente.');
            
        } catch (\Exception $e) {
            Log::error('Error eliminando perfil laboral: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Ocurrió un error al intentar eliminar el perfil laboral.');
        }
    }
}