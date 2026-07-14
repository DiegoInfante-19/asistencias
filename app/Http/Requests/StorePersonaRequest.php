<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePersonaRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'cedula' => [
                'required', 'string', 'unique:personas,cedula', 
                'regex:/^\d{6,8}$/'
            ],
            'nombres' => [
                'required', 'string', 'max:100', 
                'regex:/^[a-zA-ZñÑáéíóúÁÉÍÓÚ\s]{3,100}$/'
            ],
            'apellidos' => [
                'required', 'string', 'max:100', 
                'regex:/^[a-zA-ZñÑáéíóúÁÉÍÓÚ\s]{3,100}$/'
            ],
            'fecha_nacimiento' => [
                'required', 'date', 'before:today'
            ],
            'correo_electronico' => [
                'nullable', 'string', 'email:rfc,dns', 'max:150', 'unique:personas,correo_electronico',
                'regex:/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/'
            ],
            'lugar_nacimiento_personas' => [
                'required', 'string', 'max:255',
                // Solo letras, espacios, comas y puntos
                'regex:/^[a-zA-ZñÑáéíóúÁÉÍÓÚ\s\.,]{3,255}$/'
            ],
            'direccion' => [
                'required', 'string', 'max:500',
                // Letras, números, espacios, comas, puntos, guiones y numeral (para urbanizaciones/casas)
                'regex:/^[a-zA-Z0-9ñÑáéíóúÁÉÍÓÚ\s\.,#-]{5,500}$/'
            ],
        ];
    }

    public function messages()
    {
        return [
            'required' => 'Este campo es obligatorio.',
            'cedula.unique' => 'Esta cédula ya está registrada.',
            'correo_electronico.unique' => 'Este correo electrónico ya está registrado.',

            // Mensajes regex sincronizados
            'cedula.regex' => 'La cédula debe tener entre 6 y 8 números exactos. Sin espacios ni puntos.',
            'nombres.regex' => 'Solo letras y espacios (mínimo 3 caracteres).',
            'apellidos.regex' => 'Solo letras y espacios (mínimo 3 caracteres).',
            'correo_electronico.regex' => 'Ingrese un correo electrónico válido.',
            'lugar_nacimiento_personas.regex' => 'El lugar de nacimiento solo admite letras, espacios, comas y puntos.',
            'direccion.regex' => 'Caracteres no válidos en la dirección. Solo se permiten letras, números, y los símbolos (, . - #).',
            'fecha_nacimiento.before' => 'La fecha de nacimiento no es válida.',
        ];
    }
}