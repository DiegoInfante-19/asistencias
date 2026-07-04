<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateEstatusExpedienteRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        // Rescatamos el ID del parámetro de la ruta. 
        // Asumiremos que en web.php tu parámetro se llamará {estatus_expediente}
        $id = $this->route('estatus_expediente');

        return [
            'nombre_estatus_expediente' => [
                'required',
                'string',
                'max:50',
                Rule::unique('estatus_expedientes', 'nombre_estatus_expediente')->ignore($id, 'id_estatus_expediente'),
                'regex:/^[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ\s\-]+$/'
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'nombre_estatus_expediente.required' => 'El nombre del estatus es obligatorio.',
            'nombre_estatus_expediente.max'      => 'El nombre no debe exceder los 50 caracteres.',
            'nombre_estatus_expediente.unique'   => 'Ya existe otro estatus con este mismo nombre.',
            'nombre_estatus_expediente.regex'    => 'El formato del nombre contiene caracteres no permitidos.',
        ];
    }
}