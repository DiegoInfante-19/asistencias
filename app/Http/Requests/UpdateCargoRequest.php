<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateCargoRequest extends FormRequest{
    public function authorize(): bool{
        return true;
    }
    public function rules(): array{
        $id = $this->route('cargo');

        return [
            'descripcion_cargo' => [
                'required',
                'string',
                'max:150',
                Rule::unique('cargos', 'descripcion_cargo')->ignore($id, 'id_cargo'),
                'regex:/^[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ\s.,&\-]+$/'
            ],
        ];
    }

    public function messages(): array{
        return [
            'descripcion_cargo.required' => 'La descripción del cargo es obligatoria.',
            'descripcion_cargo.unique'   => 'Este cargo ya existe en el sistema.',
            'descripcion_cargo.regex'    => 'La descripción contiene caracteres no permitidos.',
        ];
    }
}