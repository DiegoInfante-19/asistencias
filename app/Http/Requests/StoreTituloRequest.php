<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTituloRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nombre_titulo_base' => [
                'required',
                'string',
                'max:100',
                'unique:titulos,nombre_titulo_base',
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
        'nombre_titulo_base.unique'   => 'El nombre ingresado ya se encuentra registrado en el sistema.',
        'nombre_titulo_base.regex'    => 'El formato del nombre contiene caracteres no permitidos.',
        'nivel_academico.required'    => 'Debe especificar el nivel académico del título.',
        'nivel_academico.max'         => 'El nivel académico no debe exceder los 50 caracteres.',
    ];
}
}