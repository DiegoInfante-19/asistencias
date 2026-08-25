<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCohorteRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        if ($this->has('numero_cohorte')) {
            $this->merge([
                'numero_cohorte' => strtoupper(trim($this->numero_cohorte)),
            ]);
        }
    }

    public function rules(): array
    {
        return [
            'numero_cohorte' => [
                'required',
                'string',
                'max:20',
                'unique:cohortes,numero_cohorte',
                'regex:/^[A-Z0-9\s\-]+$/' // Permite letras mayúsculas, números, espacios y guiones
            ],
            'descripcion_cohorte' => [
                'nullable', 
                'string', 
                'max:1000'
            ],
            'estatus_cohorte' => [
                'required', 
                'string', 
                'max:50'
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'numero_cohorte.required'  => 'El número de cohorte es obligatorio.',
            'numero_cohorte.unique'    => 'Este número de cohorte ya existe.',
            'numero_cohorte.regex'     => 'El número de cohorte debe contener únicamente letras, números y espacios.',
            'estatus_cohorte.required' => 'El estatus es obligatorio.',
        ];
    }
}