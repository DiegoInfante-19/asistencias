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
            'asistencias' => 'nullable|array|max:150', // Cambiamos a nullable para manejar el array vacío manualmente

            'asistencias.*.id_inscripcion_seccion' => [
                'required',
                'distinct',
                Rule::exists('inscripciones_secciones', 'id_inscripcion_seccion')
                    ->where('id_seccion', $sesion->id_seccion)
            ],
            'asistencias.*.estado'      => 'required|in:presente,ausente,justificada,tarde',
            'asistencias.*.observacion' => 'nullable|string|max:255',
        ]);

        // Si el profesor no evaluó a nadie (todos en "Ignorar"), evitamos ir a BD
        if (empty($request->asistencias) || count($request->asistencias) === 0) {
            return response()->json([
                'success' => false,
                'message' => 'No seleccionaste el estado de ningún estudiante. Selecciona al menos uno para guardar.'
            ], 422);
        }

        // ELIMINAMOS LA VALIDACIÓN DEL 100% AQUÍ. AHORA ACEPTAMOS ENVÍOS PARCIALES.

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
                'message' => 'El registro de asistencia se actualizó correctamente.'
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
