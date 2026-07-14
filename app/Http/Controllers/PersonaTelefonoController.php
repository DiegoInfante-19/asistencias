<?php

namespace App\Http\Controllers;

use App\Models\Persona;
use App\Models\TelefonoPersona;
use App\Http\Requests\StoreTelefonoPersonaRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PersonaTelefonoController extends Controller
{
    /**
     * Almacena un nuevo teléfono asociado a la persona.
     */
    public function store(StoreTelefonoPersonaRequest $request, Persona $persona)
    {
        try {
            // El request ya viene validado. 
            // Usamos la relación 'telefonos()' de la persona para que Laravel 
            // inyecte automáticamente el 'id_personas' por nosotros.
            $persona->telefonos()->create($request->validated());

            // Retornamos a la misma página (la pestaña del estudiante) con un mensaje de éxito
            // que luego será capturado por tu script de SweetAlert2 en la vista.
            return redirect()->back()->with('success', 'Teléfono agregado exitosamente.');
            
        } catch (\Exception $e) {
            Log::error('Error guardando teléfono: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Ocurrió un error al intentar agregar el teléfono.');
        }
    }

    /**
     * Elimina físicamente el teléfono de la base de datos.
     */
    public function destroy(Persona $persona, TelefonoPersona $telefono)
    {
        try {
            // Medida de seguridad Senior: 
            // Asegurarnos de que el teléfono que intentan borrar realmente pertenezca al estudiante actual.
            if ($telefono->id_personas !== $persona->id_personas) {
                return redirect()->back()->with('error', 'No tienes permiso para eliminar este registro.');
            }

            // Eliminación física directa (no hay SoftDeletes en esta tabla)
            $telefono->delete();

            return redirect()->back()->with('success', 'Teléfono eliminado correctamente.');
            
        } catch (\Exception $e) {
            Log::error('Error eliminando teléfono: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Ocurrió un error al intentar eliminar el teléfono.');
        }
    }
}