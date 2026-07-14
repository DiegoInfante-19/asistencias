<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreObservacionPersonaRequest extends FormRequest
{
    /**
     * Determina si el usuario está autorizado para hacer esta petición.
     */
    public function authorize(): bool
    {
        return true; 
    }

    /**
     * Reglas de validación que se aplicarán a la petición.
     */
    public function rules(): array
    {
        return [
            'observacion_personas' => [
                'required',
                'string',
                // Limitamos a 1000 caracteres para evitar textos excesivamente largos
                // que puedan romper el diseño o saturar la base de datos.
                'max:1000', 
            ],
        ];
    }

    /**
     * Mensajes personalizados de error en español.
     */
    public function messages(): array
    {
        return [
            'observacion_personas.required' => 'El campo de observación no puede estar vacío.',
            'observacion_personas.string'   => 'La observación debe ser un texto válido.',
            'observacion_personas.max'      => 'La observación es demasiado larga. El máximo permitido es de 1000 caracteres.',
        ];
    }
}