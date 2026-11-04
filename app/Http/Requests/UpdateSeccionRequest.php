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
        $seccionParam = $this->route('seccion');
        $seccionActual = $seccionParam instanceof \App\Models\Seccion 
                            ? $seccionParam 
                            : \App\Models\Seccion::find($seccionParam);
                            
        $seccionId = $seccionActual->id_seccion ?? $seccionParam;
        $idPeriodo = $this->input('id_periodo', $seccionActual->id_periodo ?? null);

        return [
            'id_periodo'      => ['sometimes', 'required', 'integer', 'exists:periodos_academicos,id_periodo'],
            
            // REGLA MULTI-PNF (Actualización)
            'pnfs'            => ['sometimes', 'required', 'array', 'min:1'],
            'pnfs.*'          => ['integer', 'exists:pnfs,id_pnf'],
            
            'nombre_seccion'  => [
                'sometimes', 
                'required', 
                'string', 
                'max:50',
                Rule::unique('secciones')->where(function ($query) use ($idPeriodo) {
                    return $query->where('id_periodo', $idPeriodo);
                })->ignore($seccionId, 'id_seccion')
            ],
            'estatus_seccion' => ['sometimes', 'required', 'string', 'max:50']
        ];
    }

    public function messages(): array
    {
        return [
            'nombre_seccion.unique' => 'Ya existe otra sección con este mismo nombre en el período actual.'
        ];
    }
}