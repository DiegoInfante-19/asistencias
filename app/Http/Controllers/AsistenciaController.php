<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Asistencia;
use App\Models\Sesion;
use App\Models\Profesor;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AsistenciaController extends Controller
{
    /**
     * Recibe un JSON masivo desde la vista de toma de asistencia y lo guarda en bloque de forma segura.
     */
    public function guardarLote(Request $request)
    {
        // 1. Validación estricta usando la nomenclatura real de la base de datos
        $request->validate([
            'id_sesiones'                         => 'required|exists:sesiones,id_sesiones',
            'asistencias'                         => 'required|array',
            'asistencias.*.id_inscripcion_cohortes' => 'required|exists:inscripcion_cohortes,id_inscripcion_cohortes',
            'asistencias.*.estado'                => 'required|in:Presente,Ausente,Justificado',
        ]);

        // 2. SEGURIDAD CRÍTICA: Validar que el profesor autenticado sea el dueño de la sesión
        $user = Auth::user();
        $profesor = Profesor::where('id_users', $user->id_users)->first();

        if (!$profesor) {
            return response()->json([
                'success' => false,
                'message' => 'Acceso denegado: El usuario actual no cuenta con perfil de profesor.'
            ], 403);
        }

        $sesion = Sesion::findOrFail($request->id_sesiones);

        if ($sesion->id_profesor !== $profesor->id_profesor) {
            return response()->json([
                'success' => false,
                'message' => 'Seguridad violada: No tienes permiso para modificar las asistencias de esta sesión.'
            ], 403);
        }

        // 3. Iniciamos una Transacción de Base de Datos para asegurar consistencia
        DB::beginTransaction();

        try {
            $idSesiones = $request->id_sesiones;

            // 4. Iteramos el arreglo de asistencias enviado desde JavaScript
            foreach ($request->asistencias as $registro) {
                
                // Usamos updateOrCreate directamente con las llaves de la tabla asistencias
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

            // 5. Confirmamos la transacción (Commit)
            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'El registro de asistencia se guardó correctamente.'
            ]);

        } catch (\Exception $e) {
            // Revertimos cambios si ocurre algun fallo imprevisto (Rollback)
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Ocurrió un error en el servidor: ' . $e->getMessage()
            ], 500);
        }
    }
}