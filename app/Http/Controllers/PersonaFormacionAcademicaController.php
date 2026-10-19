<?php

namespace App\Http\Controllers;

use App\Models\Persona;
use App\Models\PersonaFormacionAcademica;
use App\Models\TituloPnf;
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
            $datos = $request->validated();

            // SINCRONIZACIÓN AUTOMÁTICA:
            // Si el origen es Interno, recuperamos y asignamos automáticamente el id_titulos base 
            // asociado a través del TituloPnf seleccionado para mantener la consistencia analítica.
            if ($datos['origen_formacion'] === 'Interno' && !empty($datos['id_titulos_pnf'])) {
                $tituloPnf = TituloPnf::find($datos['id_titulos_pnf']);
                if ($tituloPnf) {
                    $datos['id_titulos'] = $tituloPnf->id_titulo;
                }
            }

            // Creamos el registro con los datos validados y sincronizados
            $persona->formacionAcademica()->create($datos);

            return redirect()->back()->with('success', 'Formación académica agregada exitosamente.');
            
        } catch (\Exception $e) {
            Log::error('Error guardando formación académica: ' . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'Ocurrió un error al intentar agregar la formación académica.');
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