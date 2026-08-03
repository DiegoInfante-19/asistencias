<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Asistencia;
use App\Models\Sesion;
use App\Models\InscripcionCohorte;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

class AsistenciaController extends Controller
{
    public function guardarLote(Request $request)
    {
        $request->validate([
            'id_sesiones'                           => 'required|exists:sesiones,id_sesiones',
            'asistencias'                           => 'required|array',
            'asistencias.*.id_inscripcion_cohortes' => 'required|exists:inscripcion_cohortes,id_inscripcion_cohortes',
            'asistencias.*.estado'                  => 'required|in:Presente,Ausente,Justificado',
        ]);

        $sesion = Sesion::findOrFail($request->id_sesiones);

        // =========================================================
        // PREVENCIÓN IDOR Y VENTANA DE TIEMPO (Policies)
        // =========================================================
        Gate::authorize('update', $sesion);

        // =========================================================
        // VALIDACIÓN DE GUARDADO PARCIAL (Integridad de Asistencia)
        // =========================================================
        // Contamos cuántos estudiantes están inscritos en el grupo de esta sesión
        $totalAlumnosInscritos = InscripcionCohorte::where('id_grupo', $sesion->id_grupo)->count();
        $totalEnviadosEnLote = count($request->asistencias);

        if ($totalEnviadosEnLote !== $totalAlumnosInscritos) {
            return response()->json([
                'success' => false,
                'message' => "Error de integridad: El lote enviado contiene {$totalEnviadosEnLote} registros, pero el grupo posee {$totalAlumnosInscritos} estudiantes inscritos. No se permiten guardados parciales."
            ], 422);
        }

        DB::beginTransaction();

        try {
            $idSesiones = $request->id_sesiones;

            foreach ($request->asistencias as $registro) {
                Asistencia::updateOrCreate(
                    [
                        'id_sesiones'             => $idSesiones,
                        'id_inscripcion_cohortes' => $registro['id_inscripcion_cohortes']
                    ],
                    [
                        'estado_asistencia'       => $registro['estado']
                    ]
                );
            }

            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'El registro de asistencia se guardó correctamente.'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Ocurrió un error en el servidor: ' . $e->getMessage()
            ], 500);
        }
    }
}