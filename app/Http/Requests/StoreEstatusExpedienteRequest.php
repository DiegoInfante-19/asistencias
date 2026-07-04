<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreEstatusExpedienteRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nombre_estatus_expediente' => [
                'required',
                'string',
                'max:50',
                'unique:estatus_expedientes,nombre_estatus_expediente',
                // Permitimos letras, números, espacios y guiones
                'regex:/^[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ\s\-]+$/' 
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'nombre_estatus_expediente.required' => 'El nombre del estatus es obligatorio.',
            'nombre_estatus_expediente.max'      => 'El nombre no debe exceder los 50 caracteres.',
            'nombre_estatus_expediente.unique'   => 'Este estatus ya se encuentra registrado en el sistema.',
            'nombre_estatus_expediente.regex'    => 'El formato del nombre contiene caracteres no permitidos.',
        ];
    }
}