<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class StoreSesionMasivaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'id_seccion' => [
                'required',
                'exists:secciones,id_seccion'
            ],
            'id_profesor' => [
                'required',
                'exists:profesores,id_profesor'
            ],
            'modo_generacion' => [
                'required',
                'in:periodo,meses'
            ],
            'meses' => [
                'required_if:modo_generacion,meses',
                'array'
            ],
            'meses.*' => [
                'string',
                'regex:/^\d{4}-\d{2}$/'
            ],
            'omitir_pasadas' => [
                'nullable',
                'boolean'
            ],
            'omitir_recesos' => [
                'nullable',
                'boolean'
            ],
            'omitir_existentes' => [
                'nullable',
                'boolean'
            ],
            'observacion_generacion' => [
                'nullable',
                'string',
                'max:500'
            ]
        ];
    }

    public function messages(): array
    {
        return [
            'id_seccion.required'       => 'Debe especificar una sección válida.',
            'id_profesor.required'      => 'Debe asignar el profesor responsable de las sesiones.',
            'modo_generacion.required'  => 'Debe seleccionar un modo de generación.',
            'meses.required_if'          => 'Debe seleccionar al menos un mes para la generación.'
        ];
    }

    /**
     * Garantiza que en AJAX las fallas de validación devuelvan respuesta JSON en lugar de redirección HTML.
     */
    protected function failedValidation(Validator $validator)
    {
        if ($this->expectsJson() || $this->ajax()) {
            throw new HttpResponseException(response()->json([
                'success' => false,
                'message' => 'Existen errores de validación en los datos enviados.',
                'errors'  => $validator->errors()
            ], 422));
        }

        parent::failedValidation($validator);
    }
}