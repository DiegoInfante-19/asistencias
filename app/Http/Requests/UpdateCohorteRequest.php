<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Models\Cohorte; // <-- Importamos el modelo

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
        // Obtenemos el ID de forma segura, ya sea que la ruta pase el objeto o el ID
        $cohorteRoute = $this->route('cohorte');
        $id = $cohorteRoute instanceof Cohorte ? $cohorteRoute->id_cohortes : $cohorteRoute;

        return [
            'numero_cohorte' => [
                'required',
                'string',
                'max:20',
                Rule::unique('cohortes', 'numero_cohorte')->ignore($id, 'id_cohortes'),
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
                'max:50',
                // REGLA PERSONALIZADA: Evita chocar con OTRA cohorte activa
                function ($attribute, $value, $fail) use ($id) {
                    if (strtolower($value) === 'activo' && Cohorte::where('estatus_cohorte', 'Activo')->where('id_cohortes', '!=', $id)->exists()) {
                        $fail('Ya existe otra cohorte activa en el sistema. Debe finalizarla antes de activar esta.');
                    }
                },
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