<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreSeccionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'id_periodo'      => ['required', 'integer', 'exists:periodos_academicos,id_periodo'],
            
            // 1. REGLA MULTI-PNF: Ahora es un array obligatorio
            'pnfs'            => ['required', 'array', 'min:1'],
            'pnfs.*'          => ['required', 'integer', 'exists:pnfs,id_pnf'],
            
            'nombre_seccion'  => [
                'required', 
                'string', 
                'max:50',
                Rule::unique('secciones')->where(function ($query) {
                    return $query->where('id_periodo', $this->id_periodo);
                })
            ],
            'estatus_seccion' => ['nullable', 'string', 'max:50']
        ];
    }

    public function messages(): array
    {
        return [
            'id_periodo.required'     => 'Debe seleccionar un período académico.',
            'id_periodo.exists'       => 'El período académico no es válido.',
            'pnfs.required'           => 'Debe asignar al menos un PNF a la sección.',
            'pnfs.min'                => 'Debe seleccionar al menos un (1) PNF para crear la sección.',
            'pnfs.*.exists'           => 'Uno de los PNF seleccionados no es válido.',
            'nombre_seccion.required' => 'El identificador de la sección es obligatorio.',
            'nombre_seccion.unique'   => 'Ya existe una sección con este mismo nombre registrada en el período académico.'
        ];
    }
}