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
                'regex:/^[A-Z0-9\s\-]+$/'
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
            // Validaciones para el período académico asociado
            'fecha_inicio' => [
                'required',
                'date'
            ],
            'fecha_fin' => [
                'required',
                'date',
                'after_or_equal:fecha_inicio'
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
            'fecha_inicio.required'    => 'La fecha de inicio del período es obligatoria.',
            'fecha_fin.required'       => 'La fecha de fin del período es obligatoria.',
            'fecha_fin.after_or_equal' => 'La fecha de fin debe ser posterior o igual a la fecha de inicio.',
        ];
    }
}