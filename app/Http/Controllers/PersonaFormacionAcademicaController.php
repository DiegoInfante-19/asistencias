<?php

namespace App\Http\Controllers;

use App\Models\Persona;
use App\Models\PersonaFormacionAcademica;
use App\Http\Requests\StorePersonaFormacionAcademicaRequest;
use Illuminate\Http\Request; 
use Illuminate\Support\Facades\Log;

class PersonaFormacionAcademicaController extends Controller
{
    /**
     * Almacena un nuevo registro de formación académica previa.
     */
    public function store(StorePersonaFormacionAcademicaRequest $request, Persona $persona)
    {
        try {
            // El request ya viene validado e integra la protección contra duplicados
            $persona->formacionAcademica()->create($request->validated());

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