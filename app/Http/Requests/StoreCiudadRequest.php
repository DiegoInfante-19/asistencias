<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreCiudadRequest extends FormRequest
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
        return [
            'id_estado'     => ['required', 'exists:estados,id_estado'],
            'nombre_ciudad' => ['required', 'string', 'max:100', 'regex:/^[a-zA-ZáéíóúÁÉÍÓÚ\s]+$/'],
        ];
    }

    public function messages(): array
    {
        return [
            'id_estado.required'     => 'Debe seleccionar un estado perteneciente a la ciudad.',
            'id_estado.exists'       => 'El estado seleccionado no es válido.',
            'nombre_ciudad.required' => 'El nombre de la ciudad es obligatorio.',
            'nombre_ciudad.unique'   => 'Esta ciudad ya está registrada en este estado.',
            'nombre_ciudad.regex'    => 'El nombre solo debe contener letras y espacios.',
            'nombre_ciudad.max'      => 'El nombre no puede exceder los 100 caracteres.',
        ];
    }
}
