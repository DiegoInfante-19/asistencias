<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;

class StorePersonaRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'primer_nombre_personas'    => $this->primer_nombre_personas ? Str::upper($this->primer_nombre_personas) : null,
            'segundo_nombre_personas'   => $this->segundo_nombre_personas ? Str::upper($this->segundo_nombre_personas) : null,
            'primer_apellido_personas'  => $this->primer_apellido_personas ? Str::upper($this->primer_apellido_personas) : null,
            'segundo_apellido_personas' => $this->segundo_apellido_personas ? Str::upper($this->segundo_apellido_personas) : null,
            // Convertimos strings vacíos a null para campos opcionales
            'email_personas'            => $this->email_personas ?: null,
            'fecha_nacimiento_personas' => $this->fecha_nacimiento_personas ?: null,
            'id_estado'                 => $this->id_estado ?: null,
            'id_ciudad'                 => $this->id_ciudad ?: null,
        ]);
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
            // FLEXIBLE: Ahora es opcional
            'fecha_nacimiento_personas' => [
                'nullable',
                'date',
                'before:today'
            ],
            // FLEXIBLE: Opcional, pero si se llena debe ser único y válido
            'email_personas' => [
                'nullable',
                'string',
                'email',
                'max:255',
                'unique:personas,email_personas',
                'regex:/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/'
            ],
            'id_lugar_nacimiento' => [
                'nullable',
                'integer',
                'exists:lugar_nacimiento_personas,id_lugar_nacimiento'
            ],
            // FLEXIBLE: Estado y ciudad opcionales
            'id_estado' => [
                'nullable',
                'integer',
                'exists:estados,id_estado'
            ],
            'id_ciudad' => [
                'nullable',
                'integer',
                'exists:ciudades,id_ciudad'
            ],
            'detalles_adicionales' => [
                'nullable',
                'string',
                'max:255'
            ],
            'id_cohortes' => [
                'nullable',
                'integer',
                'exists:cohortes,id_cohortes'
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
            'id_cohortes.required' => 'Debe asignar una cohorte de ingreso al estudiante.',
            'cedula_personas.regex' => 'La cédula debe tener entre 6 y 8 números exactos. Sin espacios ni puntos.',
            'email_personas.email' => 'Ingrese un correo electrónico válido.',
            'fecha_nacimiento_personas.before' => 'La fecha de nacimiento no es válida.',
        ];
    }
}
