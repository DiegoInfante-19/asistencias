<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePeriodoAcademicoRequest extends FormRequest{
    public function authorize(): bool{
        return true;
    }

    public function rules(): array
    {
        return [
            'id_cohortes'     => ['required', 'integer', 'exists:cohortes,id_cohortes'],
            'fecha_inicio'    => ['required', 'date'],
            'fecha_fin'       => ['required', 'date', 'after:fecha_inicio'],
            'estatus_periodo' => ['required', 'string', 'max:50']
        ];
    }

    public function messages(): array{
        return [
            'id_cohortes.required'     => 'Debe asociar el período a una cohorte.',
            'id_cohortes.exists'       => 'La cohorte seleccionada no es válida.',
            'fecha_inicio.required'    => 'La fecha de inicio es obligatoria.',
            'fecha_fin.required'       => 'La fecha de culminación es obligatoria.',
            'fecha_fin.after'          => 'La fecha de fin debe ser estrictamente posterior a la fecha de inicio.',
            'estatus_periodo.required' => 'El estatus del período es obligatorio.'
        ];
    }
}