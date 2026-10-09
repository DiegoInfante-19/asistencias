<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Models\PeriodoAcademico; // <-- Importamos el modelo

class UpdatePeriodoAcademicoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        // Obtenemos el ID de forma segura
        $periodoRoute = $this->route('periodo');
        $id = $periodoRoute instanceof PeriodoAcademico ? $periodoRoute->getKey() : $periodoRoute;

        return [
            'id_cohortes'     => ['sometimes', 'required', 'integer', 'exists:cohortes,id_cohortes'],
            'fecha_inicio'    => ['sometimes', 'required', 'date'],
            'fecha_fin'       => ['sometimes', 'required', 'date', 'after:fecha_inicio'],
            'estatus_periodo' => [
                'sometimes', 
                'required', 
                'string', 
                'max:50',
                // REGLA PERSONALIZADA
                function ($attribute, $value, $fail) use ($id) {
                    if (strtolower($value) === 'activo' && PeriodoAcademico::where('estatus_periodo', 'Activo')->where('id_periodo', '!=', $id)->exists()) {
                        $fail('Ya existe otro período académico activo. Debe finalizarlo antes de activar este.');
                    }
                },
            ]
        ];
    }

    public function messages(): array
    {
        return [
            'fecha_fin.after' => 'La fecha de fin debe ser estrictamente posterior a la fecha de inicio.'
        ];
    }
}