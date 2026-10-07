<?php

namespace App\Http\Controllers;

use App\Models\Persona;
use App\Models\InscripcionSeccion; // <-- IMPORTANTE IMPORTAR EL MODELO
use App\Models\TituloPnf;
use App\Http\Requests\StoreTitulacionPersonaRequest;
use Illuminate\Support\Facades\DB; // <-- IMPORTANTE IMPORTAR DB
use Illuminate\Support\Facades\Log;

class PersonaTitulacionController extends Controller
{
    /**
     * Almacena o actualiza el expediente académico principal de la persona.
     */
    public function store(StoreTitulacionPersonaRequest $request, Persona $persona)
    {
        try {
            // Iniciamos la transacción de base de datos
            DB::beginTransaction();

            $datosValidados = $request->validated();
            
            // 1. Detectar si hubo cambio de PNF
            $pnfActual = $persona->titulacionPersona->id_pnf ?? null;
            $nuevoPnf = $datosValidados['id_pnf'];
            $seccionesRetiradas = 0;

            // 2. Si el estudiante ya tenía PNF y lo están cambiando...
            if ($pnfActual && $pnfActual != $nuevoPnf) {
                
                // Buscamos todas las inscripciones ACTIVAS en secciones que pertenezcan al PNF viejo
                $inscripcionesIncompatibles = InscripcionSeccion::where('id_personas', $persona->id_personas)
                    ->where('estatus_inscripcion', 'Activo')
                    ->whereHas('seccion', function ($query) use ($pnfActual) {
                        $query->where('id_pnf', $pnfActual);
                    })
                    ->get();

                // Recorremos y limpiamos (Soft Delete)
                foreach ($inscripcionesIncompatibles as $inscripcion) {
                    $inscripcion->update([
                        'estatus_inscripcion' => 'Retirado por cambio de PNF'
                    ]);
                    // Al aplicar delete(), Laravel llena el deleted_at y evitamos
                    // que el ON DELETE CASCADE de la BD borre las asistencias.
                    $inscripcion->delete(); 
                    $seccionesRetiradas++;
                }
            }

            // 3. Guardamos el nuevo PNF y estatus
            $persona->titulacionPersona()->updateOrCreate(
                ['id_personas' => $persona->id_personas],
                $datosValidados
            );

            // Si todo salió bien, confirmamos la transacción
            DB::commit();

            // 4. Preparamos el mensaje de feedback
            $mensaje = 'Expediente académico guardado exitosamente.';
            if ($seccionesRetiradas > 0) {
                $mensaje .= " Además, el estudiante fue retirado automáticamente de {$seccionesRetiradas} sección(es) que ya no le corresponden.";
            }

            return redirect()->back()->with('success', $mensaje);

        } catch (\Exception $e) {
            // Si algo falla (ej. error de constraint), deshacemos todo
            DB::rollBack();
            Log::error('Error guardando expediente académico y/o limpiando secciones: ' . $e->getMessage());
            
            return redirect()->back()->with('error', 'Ocurrió un error en el servidor al actualizar el expediente.');
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