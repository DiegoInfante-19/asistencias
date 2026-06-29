<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreEstadoRequest extends FormRequest
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
            'nombre_estado' => ['required', 'string', 'max:100', 'unique:estados,nombre_estado', 'regex:/^[a-zA-ZáéíóúÁÉÍÓÚ\s]+$/'],
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
