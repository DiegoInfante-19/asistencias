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
            'cedula_personas' => [
                'required',
                'string',
                'unique:personas,cedula_personas',
                'regex:/^\d{6,8}$/'
            ],
            'primer_nombre_personas' => [
                'required',
                'string',
                'max:50',
                'regex:/^[a-zA-ZñÑáéíóúÁÉÍÓÚ\s]+$/'
            ],
            'segundo_nombre_personas' => [
                'nullable',
                'string',
                'max:50',
                'regex:/^[a-zA-ZñÑáéíóúÁÉÍÓÚ\s]*$/'
            ],
            'primer_apellido_personas' => [
                'required',
                'string',
                'max:50',
                'regex:/^[a-zA-ZñÑáéíóúÁÉÍÓÚ\s]+$/'
            ],
            'segundo_apellido_personas' => [
                'nullable',
                'string',
                'max:50',
                'regex:/^[a-zA-ZñÑáéíóúÁÉÍÓÚ\s]*$/'
            ],
            'sexo_personas' => [
                'required',
                'string',
                'in:M,F'
            ],
            'fecha_nacimiento_personas' => [
                'required',
                'date',
                'before:today'
            ],
            'email_personas' => [
                'nullable',
                'string',
                'email:rfc,dns',
                'max:255',
                'unique:personas,email_personas',
                'regex:/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/'
            ],
            // Validamos que el ID enviado exista en tu tabla de lugares de nacimiento
            'id_lugar_nacimiento' => [
                'nullable',
                'integer',
                'exists:lugar_nacimiento_personas,id_lugar_nacimiento'
            ],
        ];
    }

    public function messages()
    {
        return [
            'required' => 'Este campo es obligatorio.',
            'cedula_personas.unique' => 'Esta cédula ya está registrada.',
            'email_personas.unique' => 'Este correo electrónico ya está registrado.',
            'sexo_personas.in' => 'El género seleccionado no es válido.',
            'id_lugar_nacimiento.exists' => 'El lugar de nacimiento seleccionado no es válido.',

            // Mensajes regex
            'cedula_personas.regex' => 'La cédula debe tener entre 6 y 8 números exactos. Sin espacios ni puntos.',
            'primer_nombre_personas.regex' => 'Solo se permiten letras y espacios.',
            'segundo_nombre_personas.regex' => 'Solo se permiten letras y espacios.',
            'primer_apellido_personas.regex' => 'Solo se permiten letras y espacios.',
            'segundo_apellido_personas.regex' => 'Solo se permiten letras y espacios.',
            'email_personas.regex' => 'Ingrese un correo electrónico válido.',
            'fecha_nacimiento_personas.before' => 'La fecha de nacimiento no es válida.',
        ];
    }
}
