<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePersonaRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        // Se obtiene el ID de la ruta {persona} 
        // Asumiendo que en web.php la ruta es /personas/{persona}
        $id = $this->route('persona');

        return [
            'cedula' => [
                'required', 'string', 
                // Ignoramos el ID actual
                'unique:personas,cedula,' . $id . ',id_personas', 
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
                'nullable', 'string', 'email:rfc,dns', 'max:150',
                // Ignoramos el ID actual
                'unique:personas,correo_electronico,' . $id . ',id_personas',
                'regex:/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/'
            ],
            'lugar_nacimiento_personas' => [
                'required', 'string', 'max:255',
                'regex:/^[a-zA-ZñÑáéíóúÁÉÍÓÚ\s\.,]{3,255}$/'
            ],
            'direccion' => [
                'required', 'string', 'max:500',
                'regex:/^[a-zA-Z0-9ñÑáéíóúÁÉÍÓÚ\s\.,#-]{5,500}$/'
            ],
        ];
    }

    public function messages()
    {
        return [
            'required' => 'Este campo es obligatorio.',
            'cedula.unique' => 'Esta cédula ya está registrada en otro expediente.',
            'correo_electronico.unique' => 'Este correo electrónico ya le pertenece a otro registro.',

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