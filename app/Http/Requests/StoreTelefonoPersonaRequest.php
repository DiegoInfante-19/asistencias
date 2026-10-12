<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreTelefonoPersonaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; 
    }

    public function rules(): array
    {
        // Obtenemos la persona desde la ruta (ej: /personas/{persona}/telefonos)
        $persona = $this->route('persona');
        $personaId = $persona ? $persona->id_personas : null;

        return [
            'numero_telefono_personas' => [
                'required',
                'string',
                'min:10',
                'max:20',
                'regex:/^[\d\-\+\s\(\)]+$/',
                // BLINDAJE: El número no puede estar repetido para esta misma persona
                Rule::unique('telefonos_personas', 'numero_telefono_personas')
                    ->where('id_personas', $personaId)
            ],
            'tipo_telefono' => [
                'required',
                'string',
                'max:50',
                'in:Móvil,Fijo,Trabajo,WhatsApp,Otro',
                // BLINDAJE: La categoría no puede repetirse para esta misma persona
                Rule::unique('telefonos_personas', 'tipo_telefono')
                    ->where('id_personas', $personaId)
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'numero_telefono_personas.required' => 'Debe ingresar un número de teléfono.',
            'numero_telefono_personas.min'      => 'El número de teléfono es muy corto (mínimo 10 caracteres).',
            'numero_telefono_personas.max'      => 'El número de teléfono es muy largo (máximo 20 caracteres).',
            'numero_telefono_personas.regex'    => 'El formato del teléfono no es válido. Solo se permiten números, guiones y el signo +.',
            'numero_telefono_personas.unique'   => 'Este número de teléfono ya se encuentra registrado para este estudiante.',
            
            'tipo_telefono.required' => 'Debe seleccionar el tipo de teléfono.',
            'tipo_telefono.max'      => 'El tipo de teléfono no puede exceder los 50 caracteres.',
            'tipo_telefono.in'       => 'El tipo de teléfono seleccionado no es válido.',
            'tipo_telefono.unique'   => 'Ya existe un teléfono registrado con esta categoría (ej. Móvil o WhatsApp) para este estudiante.',
        ];
    }
}