<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Asistencia;
use App\Models\Sesion;
use App\Models\InscripcionSeccion;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;

class AsistenciaController extends Controller
{
    public function guardarLote(Request $request)
    {
        $sesion = Sesion::with('seccion')->findOrFail($request->id_sesiones);

        // BLINDAJE: Si la sección está inactiva, prohibir el registro o modificación de asistencias
        if (!$sesion->seccion->estaActiva()) {
            return response()->json([
                'success' => false,
                'message' => 'Acción denegada: La sección se encuentra inactiva. El registro de asistencia está bloqueado.'
            ], 403);
        }

        Gate::authorize('update', $sesion);

        $request->validate([
            'id_sesiones' => 'required|exists:sesiones,id_sesiones',
            'asistencias' => 'required|array|max:150',

            'asistencias.*.id_inscripcion_seccion' => [
                'required',
                'distinct',
                Rule::exists('inscripciones_secciones', 'id_inscripcion_seccion')
                    ->where('id_seccion', $sesion->id_seccion)
            ],
            'asistencias.*.estado'      => 'required|in:presente,ausente,justificada,tarde',
            'asistencias.*.observacion' => 'nullable|string|max:255',
        ]);

        $totalAlumnosInscritos = InscripcionSeccion::where('id_seccion', $sesion->id_seccion)
            ->where('estatus_inscripcion', 'Activo')
            ->count();

        $totalEnviadosEnLote = count($request->asistencias);

        if ($totalEnviadosEnLote !== $totalAlumnosInscritos) {
            return response()->json([
                'success' => false,
                'message' => "Error de integridad: El lote enviado contiene {$totalEnviadosEnLote} registros, pero la sección posee {$totalAlumnosInscritos} estudiantes activos. No se permiten guardados parciales o duplicados."
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
                        'estado_asistencia'      => $registro['estado'],
                        'observacion_asistencia' => $registro['observacion'] ?? null,
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
