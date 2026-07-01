<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreEmpresaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nombre_empresa' => [
                'required',
                'string',
                'max:150',
                'unique:empresas,nombre_empresa',
                'regex:/^[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ\s.,&\-]+$/'
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'nombre_empresa.required' => 'El nombre de la empresa es obligatorio.',
            'nombre_empresa.string'   => 'El formato del nombre no es válido.',
            'nombre_empresa.max'      => 'El nombre no puede exceder los 150 caracteres.',
            'nombre_empresa.unique'   => 'Esta empresa ya se encuentra registrada en el sistema.',
            'nombre_empresa.regex'    => 'El nombre contiene caracteres no permitidos (solo letras, números, espacios y los símbolos . , & -).',
        ];
    }
}