<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Models\PreguntaSecreta;

class SecurityQuestionsRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true; // Cambiado a true
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            // Aseguramos que el ID esté dentro del arreglo de preguntas1
            'pregunta1'  => ['required', Rule::in(array_keys(PreguntaSecreta::listaPreguntas1()))],
            'respuesta1' => 'required|string|min:3|max:150',

            // Aseguramos que el ID esté dentro del arreglo de preguntas2
            'pregunta2'  => ['required', Rule::in(array_keys(PreguntaSecreta::listaPreguntas2()))],
            'respuesta2' => 'required|string|min:3|max:150',
        ];
    }
    public function messages()
    {
        return [
            'pregunta1.in' => 'Seleccione una pregunta válida de la lista.',
            'pregunta2.in' => 'Seleccione una pregunta válida de la lista.',
            'respuesta1.min' => 'La respuesta debe ser más descriptiva (min 3 caracteres).',
        ];
    }
}
