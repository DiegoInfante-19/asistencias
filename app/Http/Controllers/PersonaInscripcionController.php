<?php

namespace App\Http\Controllers;

use App\Models\Persona;
use App\Models\InscripcionSeccion; // Reemplaza a InscripcionCohorte
use App\Http\Requests\StoreInscripcionSeccionRequest; // Reemplaza al request viejo
use Illuminate\Support\Facades\Log;

class PersonaInscripcionController extends Controller
{
    public function store(StoreInscripcionSeccionRequest $request, Persona $persona)
    {
        try {
            // El request ya validó la coherencia de PNF y exclusividad de estatus activo
            $persona->inscripcionesSecciones()->create($request->validated());

            return redirect()->back()->with('success', 'Estudiante inscrito en la sección académica exitosamente.');
            
        } catch (\Exception $e) {
            Log::error('Error inscribiendo al estudiante en la sección: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Ocurrió un error al intentar registrar la inscripción.');
        }
    }

    public function destroy(Persona $persona, InscripcionSeccion $inscripcion)
    {
        try {
            if ($inscripcion->id_personas !== $persona->id_personas) {
                return redirect()->back()->with('error', 'No tienes permiso para eliminar esta inscripción.');
            }

            $inscripcion->delete();

            return redirect()->back()->with('success', 'Inscripción de sección retirada correctamente.');
            
        } catch (\Exception $e) {
            Log::error('Error anulando inscripción de sección: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Ocurrió un error al intentar anular la inscripción.');
        }
    }
}