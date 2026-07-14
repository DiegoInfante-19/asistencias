<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTelefonoPersonaRequest extends FormRequest
{
    /**
     * Determina si el usuario está autorizado para hacer esta petición.
     */
    public function authorize(): bool
    {
        // Como la seguridad se maneja a nivel de rutas/middlewares, 
        // aquí lo dejamos en true para permitir el paso.
        return true; 
    }

    /**
     * Reglas de validación que se aplicarán a la petición.
     */
    public function rules(): array
    {
        return [
            'numero_telefono_personas' => [
                'required',
                'string',
                'min:10',
                'max:20',
                // Expresión regular que permite números, espacios, guiones, y el signo +
                'regex:/^[\d\-\+\s\(\)]+$/',
            ],
            'tipo_telefono' => [
                'required',
                'string',
                'max:50',
                // Opcional: Si quieres forzar a que solo seleccionen estas opciones del <select>
                'in:Móvil,Local,Trabajo,Emergencia,Otro'
            ],
        ];
    }

    /**
     * Mensajes personalizados de error en español.
     */
    public function messages(): array
    {
        return [
            'numero_telefono_personas.required' => 'Debe ingresar un número de teléfono.',
            'numero_telefono_personas.min'      => 'El número de teléfono es muy corto (mínimo 10 caracteres).',
            'numero_telefono_personas.max'      => 'El número de teléfono es muy largo (máximo 20 caracteres).',
            'numero_telefono_personas.regex'    => 'El formato del teléfono no es válido. Solo se permiten números, guiones y el signo +.',
            
            'tipo_telefono.required' => 'Debe seleccionar el tipo de teléfono.',
            'tipo_telefono.max'      => 'El tipo de teléfono no puede exceder los 50 caracteres.',
            'tipo_telefono.in'       => 'El tipo de teléfono seleccionado no es válido.',
        ];
    }
}