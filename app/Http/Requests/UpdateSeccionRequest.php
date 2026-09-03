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
        $seccionId = $this->route('seccion');

        // Si id_periodo o id_pnf no viajan en el request de actualización, los rescatamos del modelo actual para que la regla unique no falle
        $seccionActual = \App\Models\Seccion::find($seccionId);
        $idPeriodo = $this->input('id_periodo', $seccionActual->id_periodo ?? null);
        $idPnf = $this->input('id_pnf', $seccionActual->id_pnf ?? null);

        return [
            'id_periodo'      => ['sometimes', 'required', 'integer', 'exists:periodos_academicos,id_periodo'],
            'id_pnf'          => ['sometimes', 'required', 'integer', 'exists:pnfs,id_pnf'],
            'nombre_seccion'  => [
                'sometimes', 
                'required', 
                'string', 
                'max:50',
                Rule::unique('secciones')->where(function ($query) use ($idPeriodo, $idPnf) {
                    return $query->where('id_periodo', $idPeriodo)
                                   ->where('id_pnf', $idPnf);
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