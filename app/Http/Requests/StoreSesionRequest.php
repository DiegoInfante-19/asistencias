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
            // Valida el grupo seleccionado por el docente
            'id_grupo' => ['required', 'exists:grupos_academicos,id_grupo'],
            
            // La fecha no puede ser futura
            'fecha_sesion' => ['required', 'date', 'before_or_equal:today'],
            
            // Verificamos el nombre exacto de tu campo de observaciones en base de datos (observaciones_sesiones)
            'observaciones_sesiones' => ['nullable', 'string', 'max:1000'],
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
            
            'fecha_sesion.required' => 'La fecha de la sesión es obligatoria.',
            'fecha_sesion.date'     => 'Debe ingresar un formato de fecha válido.',
            'fecha_sesion.before_or_equal' => 'No puedes registrar clases con fechas futuras.',
            
            'observaciones_sesiones.max' => 'La observación no puede superar los 1000 caracteres.',
        ];
    }
}