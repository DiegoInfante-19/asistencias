<?php

namespace App\Http\Controllers;

use App\Models\Persona;
use App\Models\InscripcionSeccion;
use App\Models\TituloPnf;
use App\Models\TitulacionPersona;
use App\Http\Requests\StoreTitulacionPersonaRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PersonaTitulacionController extends Controller
{
    /**
     * Almacena o actualiza el expediente académico principal de la persona.
     */
    public function store(StoreTitulacionPersonaRequest $request, Persona $persona)
    {
        try {
            DB::beginTransaction();

            $datosValidados = $request->validated();

            // === 1. LÓGICA DE AUTOCOMPLETADO DEL TÍTULO BASE ===
            $tituloEspecifico = TituloPnf::findOrFail($datosValidados['id_titulos_pnf']);
            $datosValidados['id_titulacion'] = $tituloEspecifico->id_titulo;

            // === 2. DETECCIÓN DE CAMBIO DE PNF ===
            $pnfActual = $persona->titulacionPersona->id_pnf ?? null;
            $nuevoPnf  = $datosValidados['id_pnf'];
            $seccionesRetiradas = 0;

            if ($pnfActual && $pnfActual != $nuevoPnf) {
                $inscripcionesIncompatibles = InscripcionSeccion::where('id_personas', $persona->id_personas)
                    ->where('estatus_inscripcion', 'Activo')
                    ->whereHas('seccion', function ($query) use ($pnfActual) {
                        $query->where('id_pnf', $pnfActual);
                    })
                    ->get();

                foreach ($inscripcionesIncompatibles as $inscripcion) {
                    $inscripcion->update([
                        'estatus_inscripcion' => 'Retirado por cambio de PNF'
                    ]);
                    $inscripcion->delete();
                    $seccionesRetiradas++;
                }
            }

            // === 3. GUARDADO FINAL CORREGIDO ===
            // Búsqueda única por estudiante para actualizar o crear su expediente principal
            TitulacionPersona::updateOrCreate(
                [
                    'id_personas' => $persona->id_personas
                ],
                [
                    'id_pnf'                => $datosValidados['id_pnf'],
                    'id_titulacion'         => $datosValidados['id_titulacion'],
                    'id_titulos_pnf'        => $datosValidados['id_titulos_pnf'],
                    'id_estatus_expediente' => $datosValidados['id_estatus_expediente'] ?? null
                ]
            );

            DB::commit();

            // === 4. FEEDBACK AL USUARIO ===
            $mensaje = 'Expediente académico guardado exitosamente.';
            if ($seccionesRetiradas > 0) {
                $mensaje .= " Además, el estudiante fue retirado automáticamente de {$seccionesRetiradas} sección(es) del PNF anterior.";
            }

            return redirect()->back()->with('success', $mensaje);
            
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error guardando expediente académico: ' . $e->getMessage());

            return redirect()->back()->with('error', 'Ocurrió un error en el servidor al actualizar el expediente.');
        }
    }

    /**
     * Endpoint API para cargar los títulos dependientes de un PNF
     */
    public function getTitulosPorPnf($id_pnf)
    {
        $titulos = TituloPnf::where('id_pnf', $id_pnf)->get();

        // Expone id_titulos_pnf, id_titulo y nombre_titulo_pnf
        $data = $titulos->map(function ($item) {
            return [
                'id_titulos_pnf'    => $item->id_titulos_pnf,
                'id_titulo'         => $item->id_titulo,
                'nombre_titulo_pnf' => $item->nombre_titulo_pnf
            ];
        });
        
        return response()->json($data);
    }
}