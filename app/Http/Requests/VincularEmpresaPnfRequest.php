<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class VincularEmpresaPnfRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            // Verificamos que la empresa exista
            'id_empresa' => [
                'required', 
                'exists:empresas,id_empresa'
            ],
            'tipo_relacion' => [
                'required', 
                'string', 
                'max:100',
                'regex:/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/'
            ],
            'observacion_empresa_pnf' => [
                'nullable', 
                'string', 
                'max:1000'
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'id_empresa.required' => 'Debe seleccionar una empresa aliada.',
            'id_empresa.exists'   => 'La empresa seleccionada no se encuentra en el sistema.',
            'tipo_relacion.required' => 'Debe especificar el tipo de relación o convenio.',
            'tipo_relacion.max'      => 'El tipo de relación no debe exceder los 100 caracteres.',
            'tipo_relacion.regex'    => 'El tipo de relación solo debe contener letras y espacios.',
            'observacion_empresa_pnf.max' => 'Las observaciones no pueden exceder los 1000 caracteres.',
        ];
    }
}