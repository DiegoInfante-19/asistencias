<?php

namespace App\Http\Controllers;

use App\Models\Persona;
use App\Http\Requests\StoreTitulacionPersonaRequest;
use Illuminate\Support\Facades\Log;
use App\Models\TituloPnf;

class PersonaTitulacionController extends Controller
{
    /**
     * Almacena o actualiza el expediente académico principal de la persona.
     */
    public function store(StoreTitulacionPersonaRequest $request, Persona $persona)
    {
        try {
            // =================================================================
            // LA REGLA 1 A 1 (Actualizar o Crear)
            // =================================================================
            // Asumiendo que en tu modelo Persona.php tienes definida la relación 
            // como: public function titulacion() { return $this->hasOne(...); }

            $persona->titulacionPersona()->updateOrCreate(
                ['id_personas' => $persona->id_personas],
                $request->validated()
            );

            return redirect()->back()->with('success', 'Expediente académico guardado exitosamente.');
        } catch (\Exception $e) {
            Log::error('Error guardando expediente académico: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Ocurrió un error al guardar el expediente académico.');
        }
    }

    public function getTitulosPorPnf($id_pnf)
    {
        // Carga la relación 'titulo' para obtener el nombre real desde la tabla de títulos
        $titulos = TituloPnf::with('titulo')
            ->where('id_pnf', $id_pnf)
            ->get();
        // Transformamos los datos para que el JS los entienda fácil
        $data = $titulos->map(function ($item) {
            return [
                'id_titulo' => $item->id_titulo,
                'nombre_titulo_pnf' => $item->nombre_titulo_pnf
            ];
        });
        return response()->json($data);
    }
}
