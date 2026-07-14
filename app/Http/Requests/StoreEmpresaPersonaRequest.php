<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreEmpresaPersonaRequest extends FormRequest
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
            'id_empresa' => [
                'required',
                'integer',
                // Verifica que el ID enviado realmente exista en la tabla 'empresas'
                // asumiendo que tu llave primaria ahí es 'id_empresa'
                'exists:empresas,id_empresa', 
            ],
            'id_cargo' => [
                'required',
                'integer',
                // Verifica que el ID enviado realmente exista en la tabla 'cargos'
                // asumiendo que tu llave primaria ahí es 'id_cargo'
                'exists:cargos,id_cargo',
            ],
        ];
    }

    /**
     * Mensajes personalizados de error en español.
     */
    public function messages(): array
    {
        return [
            'id_empresa.required' => 'Debe seleccionar una empresa.',
            'id_empresa.integer'  => 'El identificador de la empresa no es válido.',
            'id_empresa.exists'   => 'La empresa seleccionada no se encuentra registrada en el sistema.',
            
            'id_cargo.required'   => 'Debe seleccionar un cargo.',
            'id_cargo.integer'    => 'El identificador del cargo no es válido.',
            'id_cargo.exists'     => 'El cargo seleccionado no se encuentra registrado en el sistema.',
        ];
    }
}