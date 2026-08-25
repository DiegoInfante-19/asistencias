<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateCohorteRequest extends FormRequest
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
        // Nota: Asegúrate de que el parámetro de ruta en web.php coincida (ej. {cohorte})
        $id = $this->route('cohorte');

        return [
            'numero_cohorte' => [
                'required',
                'string',
                'max:20',
                Rule::unique('cohortes', 'numero_cohorte')->ignore($id, 'id_cohortes'),
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