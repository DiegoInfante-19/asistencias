<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCohorteRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'numero_cohorte' => [
                'required',
                'string',
                'max:20',
                'unique:cohortes,numero_cohorte',
                'regex:/^[a-zA-Z0-9\s]+$/'
            ],
            'fecha_inicio_cohorte' => ['required', 'date'],
            'fecha_fin_cohorte'    => ['required', 'date', 'after:fecha_inicio_cohorte'],
            'descripcion_cohorte'  => ['nullable', 'string', 'max:1000', 'regex:/^[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ\s.,\-]+$/'],
            'estatus_cohorte'      => ['required', 'string', 'max:50'],
        ];
    }

    public function messages(): array
    {
        return [
            'numero_cohorte.required'       => 'El número de cohorte es obligatorio.',
            'numero_cohorte.unique'         => 'Este número de cohorte ya existe.',
            'numero_cohorte.regex'          => 'El formato del número no es válido.',
            'fecha_fin_cohorte.after'       => 'La fecha de fin debe ser posterior a la fecha de inicio.',
            'descripcion_cohorte.regex'     => 'La descripción contiene caracteres no permitidos.',
            'estatus_cohorte.required'      => 'El estatus es obligatorio.',
        ];
    }
}