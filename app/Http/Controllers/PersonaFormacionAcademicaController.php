<?php

namespace App\Http\Controllers;

use App\Models\Persona;
use App\Models\PersonaFormacionAcademica;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PersonaFormacionAcademicaController extends Controller
{
    /**
     * Almacena un nuevo registro de formación académica previa.
     */
    public function store(Request $request, Persona $persona)
    {
        try {
            // Se valida directamente aquí para forzar la inyección de origen_formacion
            $validated = $request->validate([
                'id_titulos'                      => 'nullable|exists:titulos,id_titulos',
                'id_titulos_pnf'                  => 'nullable|exists:titulos_pnf,id_titulos_pnf',
                'observacion_formacion_academica' => 'nullable|string',
                'origen_formacion'                => 'required|in:Interno,Externo',
            ]);

            // Validación de regla de negocio: Debe traer al menos uno de los dos títulos
            if (empty($validated['id_titulos']) && empty($validated['id_titulos_pnf'])) {
                return redirect()->back()->with('error', 'Debe seleccionar un título base o un título PNF.');
            }

            $persona->formacionAcademica()->create($validated);

            return redirect()->back()->with('success', 'Formación académica agregada exitosamente.');
            
        } catch (\Exception $e) {
            Log::error('Error guardando formación académica: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Ocurrió un error al intentar agregar la formación académica.');
        }
    }

    /**
     * Elimina un registro de formación académica.
     */
    public function destroy(Persona $persona, PersonaFormacionAcademica $formacion)
    {
        try {
            if ($formacion->id_personas !== $persona->id_personas) {
                return redirect()->back()->with('error', 'No tienes permiso para eliminar este registro.');
            }

            $formacion->delete();

            return redirect()->back()->with('success', 'Formación académica eliminada correctamente.');
            
        } catch (\Exception $e) {
            Log::error('Error eliminando formación académica: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Ocurrió un error al intentar eliminar la formación académica.');
        }
    }
}