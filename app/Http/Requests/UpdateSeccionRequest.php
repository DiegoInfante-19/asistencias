<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateSeccionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        // Rescatamos el ID de la sección desde la ruta (ej: {seccion})
        $seccionId = $this->route('seccion');

        return [
            'id_periodo'      => ['sometimes', 'required', 'integer', 'exists:periodos_academicos,id_periodo'],
            'id_pnf'          => ['sometimes', 'required', 'integer', 'exists:pnfs,id_pnf'],
            'nombre_seccion'  => [
                'sometimes', 
                'required', 
                'string', 
                'max:50',
                Rule::unique('secciones')->where(function ($query) {
                    return $query->where('id_periodo', $this->id_periodo)
                                 ->where('id_pnf', $this->id_pnf);
                })->ignore($seccionId, 'id_seccion')
            ],
            'estatus_seccion' => ['sometimes', 'required', 'string', 'max:50']
        ];
    }

    public function messages(): array
    {
        return [
            'nombre_seccion.unique' => 'Ya existe otra sección con este mismo nombre en el PNF y período actual.'
        ];
    }
}