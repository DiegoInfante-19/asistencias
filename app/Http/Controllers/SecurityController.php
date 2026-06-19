<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\PreguntaSecreta;

class SecurityController extends Controller
{
    /**
     * Valida, encripta y guarda las respuestas de seguridad del usuario autenticado.
     */
    public function storePreguntas(Request $request)
    {
        // 1. Validación estricta obligando a usar los IDs exactos del HTML
        $validator = Validator::make($request->all(), [
            'pregunta1'  => 'required|in:11,12,13,14,15',
            'respuesta1' => 'required|string|min:3',
            'pregunta2'  => 'required|in:21,22,23,24,25',
            'respuesta2' => 'required|string|min:3',
        ], [
            'required' => 'Este campo es obligatorio.',
            'min'      => 'La respuesta debe tener al menos 3 caracteres.',
            'in'       => 'La pregunta seleccionada no es válida.',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // 2. Identificar al usuario y buscar su registro en blanco
        $user = auth()->user();
        $preguntas = PreguntaSecreta::where('id_users', $user->id_users)->first();

        if (!$preguntas) {
            return back()->with('error', 'Error de integridad: No se encontró el registro de seguridad para este usuario.');
        }

        // 3. Actualizamos y Encriptamos las respuestas forzando MAYÚSCULAS
        $preguntas->update([
            'pregunta1'  => $request->pregunta1, // Guardará el número (ej: 12)
            'respuesta1' => Hash::make(mb_strtoupper(trim($request->respuesta1), 'UTF-8')),

            'pregunta2'  => $request->pregunta2, // Guardará el número (ej: 24)
            'respuesta2' => Hash::make(mb_strtoupper(trim($request->respuesta2), 'UTF-8')),
        ]);

        // 4. Desbloqueo exitoso
        return back()->with('success', 'Preguntas de seguridad configuradas exitosamente. El sistema ha sido desbloqueado.');
    }
}