<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdatePersonaRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        // Al usar Route Model Binding en Laravel, la variable contiene el modelo completo.
        // Accedemos a su clave primaria para ignorarla en las reglas 'unique'.
        $id = $this->route('persona')->id_personas;

        return [
            'cedula_personas' => [
                'required', 'string', 
                Rule::unique('personas', 'cedula_personas')->ignore($id, 'id_personas'),
                'regex:/^\d{6,8}$/'
            ],
            'primer_nombre_personas' => [
                'required', 'string', 'max:50', 
                'regex:/^[a-zA-ZñÑáéíóúÁÉÍÓÚ\s]+$/'
            ],
            'segundo_nombre_personas' => [
                'nullable', 'string', 'max:50', 
                'regex:/^[a-zA-ZñÑáéíóúÁÉÍÓÚ\s]*$/'
            ],
            'primer_apellido_personas' => [
                'required', 'string', 'max:50', 
                'regex:/^[a-zA-ZñÑáéíóúÁÉÍÓÚ\s]+$/'
            ],
            'segundo_apellido_personas' => [
                'nullable', 'string', 'max:50', 
                'regex:/^[a-zA-ZñÑáéíóúÁÉÍÓÚ\s]*$/'
            ],
            'sexo_personas' => [
                'required', 'string', 'in:M,F'
            ],
            'fecha_nacimiento_personas' => [
                'required', 'date', 'before:today'
            ],
            'email_personas' => [
                'nullable', 'string', 'email:rfc,dns', 'max:255',
                Rule::unique('personas', 'email_personas')->ignore($id, 'id_personas'),
                'regex:/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/'
            ],
            
            // --- NUEVA LÓGICA DE LUGAR DE NACIMIENTO ---
            
            // El campo original es nullable porque se procesará manual en el controlador
            'id_lugar_nacimiento' => [
                'nullable', 'integer', 'exists:lugar_nacimiento_personas,id_lugar_nacimiento'
            ],
            // Validamos los pedazos que componen el lugar de nacimiento desde la vista
            'id_estado' => [
                'required', 'integer', 'exists:estados,id_estado'
            ],
            'id_ciudad' => [
                'required', 'integer', 'exists:ciudades,id_ciudad'
            ],
            'detalles_adicionales' => [
                'nullable', 'string', 'max:255'
            ],
        ];
    }

    public function messages()
    {
        return [
            'required' => 'Este campo es obligatorio.',
            'cedula_personas.unique' => 'Esta cédula ya está registrada en otro expediente.',
            'email_personas.unique' => 'Este correo electrónico ya le pertenece a otro registro.',
            'sexo_personas.in' => 'El género seleccionado no es válido.',
            
            // Mensajes para el lugar de nacimiento dinámico
            'id_estado.required' => 'Debe seleccionar un estado.',
            'id_estado.exists' => 'El estado seleccionado no es válido.',
            'id_ciudad.required' => 'Debe seleccionar una ciudad.',
            'id_ciudad.exists' => 'La ciudad seleccionada no es válida.',
            'id_lugar_nacimiento.exists' => 'El lugar de nacimiento procesado no es válido.',

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