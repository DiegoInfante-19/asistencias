<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePnfRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nombre_pnf' => [
                'required',
                'string',
                'max:100',
                'unique:pnfs,nombre_pnf',
                'regex:/^[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ\s.,\-]+$/'
            ],
            'descripcion_pnf' => [
                'nullable',
                'string',
                'max:1000'
            ],
            'vigencia_pnf' => [
                'required',
                'boolean'
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'nombre_pnf.required'  => 'El nombre del PNF es obligatorio.',
            'nombre_pnf.max'       => 'El nombre no puede exceder los 100 caracteres.',
            'nombre_pnf.unique'    => 'Este PNF ya se encuentra registrado.',
            'nombre_pnf.regex'     => 'El formato del nombre contiene caracteres no permitidos.',
            'vigencia_pnf.required'=> 'Debe especificar la vigencia del PNF.',
            'vigencia_pnf.boolean' => 'El valor de vigencia no es válido.',
        ];
    }
}