<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateEstadoRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $id = $this->route('estado'); // Ajusta según el nombre de tu parámetro en la ruta
        return [
            'nombre_estado' => [
                'required',
                'string',
                'max:100',
                \Illuminate\Validation\Rule::unique('estados', 'nombre_estado')->ignore($id, 'id_estado'),
                'regex:/^[a-zA-ZáéíóúÁÉÍÓÚ\s]+$/'
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'nombre_estado.required' => 'El nombre del estado es obligatorio.',
            'nombre_estado.unique'   => 'Este estado ya se encuentra registrado en el sistema.',
            'nombre_estado.regex'    => 'El nombre solo debe contener letras y espacios.',
            'nombre_estado.max'      => 'El nombre no puede exceder los 100 caracteres.',
        ];
    }
}
