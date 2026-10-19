<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;

class UpdatePersonaRequest extends FormRequest
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
            'email_personas'            => $this->email_personas ?: null,
            'fecha_nacimiento_personas' => $this->fecha_nacimiento_personas ?: null,
            'id_estado'                 => $this->id_estado ?: null,
            'id_ciudad'                 => $this->id_ciudad ?: null,
        ]);
    }

    public function rules()
    {
        $id = $this->route('persona')->id_personas;

        return [
            'cedula_personas' => [
                'required',
                'string',
                Rule::unique('personas', 'cedula_personas')->ignore($id, 'id_personas'),
                'regex:/^\d{6,8}$/'
            ],
            'primer_nombre_personas' => ['required', 'string', 'max:50', 'regex:/^[a-zA-ZñÑáéíóúÁÉÍÓÚ\s]+$/'],
            'segundo_nombre_personas' => ['nullable', 'string', 'max:50', 'regex:/^[a-zA-ZñÑáéíóúÁÉÍÓÚ\s]*$/'],
            'primer_apellido_personas' => ['required', 'string', 'max:50', 'regex:/^[a-zA-ZñÑáéíóúÁÉÍÓÚ\s]+$/'],
            'segundo_apellido_personas' => ['nullable', 'string', 'max:50', 'regex:/^[a-zA-ZñÑáéíóúÁÉÍÓÚ\s]*$/'],
            'sexo_personas' => ['required', 'string', 'in:M,F'],

            // FLEXIBLES
            'fecha_nacimiento_personas' => ['nullable', 'date', 'before:today'],
            'email_personas' => [
                'nullable',
                'string',
                'email',
                'max:255',
                Rule::unique('personas', 'email_personas')->ignore($id, 'id_personas'),
            ],

            'id_lugar_nacimiento' => ['nullable', 'integer', 'exists:lugar_nacimiento_personas,id_lugar_nacimiento'],
            'id_estado' => ['nullable', 'integer', 'exists:estados,id_estado'],
            'id_ciudad' => ['nullable', 'integer', 'exists:ciudades,id_ciudad'],
            'detalles_adicionales' => ['nullable', 'string', 'max:255'],
            'id_cohortes' => ['nullable', 'integer', 'exists:cohortes,id_cohortes'],
        ];
    }
}
