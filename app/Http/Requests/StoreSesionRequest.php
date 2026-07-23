<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreSesionRequest extends FormRequest
{
    /**
     * Determina si el usuario está autorizado para realizar esta petición.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Obtiene las reglas de validación aplicables a la petición.
     */
    public function rules(): array
    {
        return [
            // CORREGIDO: Exige y valida el id_grupo en lugar de la cohorte global
            'id_grupo' => ['required', 'exists:grupos_academicos,id_grupo'],
            
            'id_profesor' => ['required', 'exists:profesores,id_profesor'],
            'fecha_sesion' => ['required', 'date'],
            'observacion_sesion' => ['nullable', 'string', 'max:1000'],
        ];
    }

    /**
     * Mensajes de error personalizados para el usuario.
     */
    public function messages(): array
    {
        return [
            'id_grupo.required' => 'Debe seleccionar el grupo académico al que pertenece la sesión.',
            'id_grupo.exists'   => 'El grupo académico seleccionado no es válido.',
            'id_profesor.required' => 'Debe indicar el profesor que dictó la sesión.',
            'id_profesor.exists'   => 'El profesor seleccionado no existe.',
            'fecha_sesion.required' => 'La fecha y hora de la sesión es obligatoria.',
            'fecha_sesion.date'     => 'La fecha debe ser un formato de fecha y hora válido.',
            'observacion_sesion.max' => 'La observación no puede superar los 1000 caracteres.',
        ];
    }
}