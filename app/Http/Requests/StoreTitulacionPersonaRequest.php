<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTitulacionPersonaRequest extends FormRequest
{
    /**
     * Determina si el usuario está autorizado para hacer esta petición.
     */
    public function authorize(): bool
    {
        return true; 
    }

    /**
     * Reglas de validación que se aplicarán a la petición.
     */
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
            'id_estatus_expediente' => [
                'nullable',
                'integer',
                'exists:estatus_expedientes,id_estatus_expediente',
            ],
        ];
    }

    /**
     * Mensajes personalizados de error en español.
     */
    public function messages(): array
    {
        return [
            'id_titulacion.integer'  => 'El identificador de la titulación no es válido.',
            'id_titulacion.exists'   => 'La titulación seleccionada no se encuentra registrada en el sistema.',
            
            'id_pnf.required'        => 'Debe seleccionar un PNF.',
            'id_pnf.integer'         => 'El identificador del PNF no es válido.',
            'id_pnf.exists'          => 'El PNF seleccionado no se encuentra registrado en el sistema.',

            'id_estatus_expediente.integer'  => 'El identificador del estatus no es válido.',
            'id_estatus_expediente.exists'   => 'El estatus de expediente seleccionado no existe.',
        ];
    }
}