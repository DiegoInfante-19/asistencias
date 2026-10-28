<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreTitulacionPersonaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'id_titulacion' => [
                'nullable',
                'integer',
                'exists:titulos,id_titulos',
            ],
            'id_pnf' => [
                'required',
                'integer',
                'exists:pnfs,id_pnf',
            ],
            'id_titulos_pnf' => [
                'required', 
                'integer', 
                // VALIDACIÓN CONTEXTUAL: Comprueba que el id_titulos_pnf corresponda al id_pnf recibido
                Rule::exists('titulos_pnf', 'id_titulos_pnf')->where(function ($query) {
                    $query->where('id_pnf', $this->id_pnf);
                }),
            ], 
            'id_estatus_expediente' => [
                'nullable',
                'integer',
                'exists:estatus_expedientes,id_estatus_expediente',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'id_titulacion.integer'          => 'El identificador de la titulación no es válido.',
            'id_titulacion.exists'           => 'La titulación seleccionada no se encuentra registrada en el sistema.',

            'id_pnf.required'                => 'Debe seleccionar un PNF.',
            'id_pnf.integer'                 => 'El identificador del PNF no es válido.',
            'id_pnf.exists'                  => 'El PNF seleccionado no se encuentra registrado en el sistema.',

            'id_titulos_pnf.required'        => 'Debe seleccionar una titulación específica.',
            'id_titulos_pnf.exists'          => 'La titulación seleccionada no pertenece al PNF indicado.',

            'id_estatus_expediente.integer'  => 'El identificador del estatus no es válido.',
            'id_estatus_expediente.exists'   => 'El estatus de expediente seleccionado no existe.',
        ];
    }
}