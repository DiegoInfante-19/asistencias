<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateTituloRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        // Nota: Asegúrate de que en la ruta la variable se llame {titulo}
        $id = $this->route('titulo');

        return [
            'nombre_titulo_base' => [
                'required',
                'string',
                'max:100',
                Rule::unique('titulos', 'nombre_titulo_base')->ignore($id, 'id_titulos'),
                'regex:/^[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ\s\-]+$/'
            ],
            'nivel_academico' => [
                'required',
                'string',
                'max:50'
            ],
        ];
    }
    public function messages(): array
    {
        return [
            'nombre_titulo_base.required' => 'El nombre del título es obligatorio.',
            'nombre_titulo_base.max'      => 'El nombre no debe exceder los 100 caracteres.',
            'nombre_titulo_base.unique'   => 'Ya existe otro título con este mismo nombre.',
            'nombre_titulo_base.regex'    => 'El formato del nombre contiene caracteres no permitidos.',
            'nivel_academico.required'    => 'El nivel académico es obligatorio.',
            'nivel_academico.max'         => 'El nivel académico no debe exceder los 50 caracteres.',
        ];
    }
}
