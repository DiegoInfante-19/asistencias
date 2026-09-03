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
            'id_pnf'          => ['required', 'integer', 'exists:pnfs,id_pnf'],
            'nombre_seccion'  => [
                'required', 
                'string', 
                'max:50', // Sin filtros de formato rígidos: total flexibilidad para texto libre o notas del admin
                Rule::unique('secciones')->where(function ($query) {
                    return $query->where('id_periodo', $this->id_periodo)
                                   ->where('id_pnf', $this->id_pnf);
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
            'id_pnf.required'         => 'Debe seleccionar un PNF.',
            'id_pnf.exists'           => 'El PNF seleccionado no es válido.',
            'nombre_seccion.required' => 'El identificador de la sección es obligatorio.',
            'nombre_seccion.unique'   => 'Ya existe una sección con este mismo nombre registrada en este PNF y período académico.'
        ];
    }
}