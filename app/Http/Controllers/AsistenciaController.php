<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Asistencia;
use App\Models\Sesion;
use App\Models\InscripcionSeccion;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

class AsistenciaController extends Controller
{
    public function guardarLote(Request $request)
    {
        $request->validate([
            'id_sesiones'                                => 'required|exists:sesiones,id_sesiones',
            'asistencias'                                => 'required|array',
            'asistencias.*.id_inscripcion_seccion'       => 'required|exists:inscripciones_secciones,id_inscripcion_seccion',
            'asistencias.*.estado'                       => 'required|in:Presente,Ausente,Justificado',
        ]);

        $sesion = Sesion::findOrFail($request->id_sesiones);

        // Prevención IDOR y ventana de tiempo
        Gate::authorize('update', $sesion);

        // Contamos cuántos estudiantes están inscritos en la sección mixta de esta sesión
        $totalAlumnosInscritos = InscripcionSeccion::where('id_seccion', $sesion->id_seccion)
            ->where('estatus_inscripcion', 'Activo')
            ->count();
            
        $totalEnviadosEnLote = count($request->asistencias);

        // Seguridad estricta: No se permiten guardados parciales. Debe coincidir el número exacto.
        if ($totalEnviadosEnLote !== $totalAlumnosInscritos) {
            return response()->json([
                'success' => false,
                'message' => "Error de integridad: El lote enviado contiene {$totalEnviadosEnLote} registros, pero la sección posee {$totalAlumnosInscritos} estudiantes activos. No se permiten guardados parciales."
            ], 422);
        }

        DB::beginTransaction();

        try {
            $idSesiones = $request->id_sesiones;

            foreach ($request->asistencias as $registro) {
                Asistencia::updateOrCreate(
                    [
                        'id_sesiones'            => $idSesiones,
                        'id_inscripcion_seccion' => $registro['id_inscripcion_seccion']
                    ],
                    [
                        'estado_asistencia'      => $registro['estado']
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