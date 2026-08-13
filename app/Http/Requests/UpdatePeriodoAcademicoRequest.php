<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdatePeriodoAcademicoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'id_cohortes'     => ['sometimes', 'required', 'integer', 'exists:cohortes,id_cohortes'],
            'fecha_inicio'    => ['sometimes', 'required', 'date'],
            'fecha_fin'       => ['sometimes', 'required', 'date', 'after:fecha_inicio'],
            'estatus_periodo' => ['sometimes', 'required', 'string', 'max:50']
        ];
    }

    public function messages(): array
    {
        return [
            'fecha_fin.after' => 'La fecha de fin debe ser estrictamente posterior a la fecha de inicio.'
        ];
    }
}