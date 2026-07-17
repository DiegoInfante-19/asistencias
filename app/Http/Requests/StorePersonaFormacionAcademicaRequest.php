<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePersonaFormacionAcademicaRequest extends FormRequest
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
            'id_titulos' => [
                'nullable', // Puede venir vacío...
                'required_without:id_titulos_pnf', // ...PERO es obligatorio si id_titulos_pnf está vacío
                'integer',
                'exists:titulos,id_titulos',
            ],
            'id_titulos_pnf' => [
                'nullable',
                'required_without:id_titulos',
                'integer',
                'exists:titulos_pnf,id_titulos_pnf', // <--- Asegúrate que sea 'titulos_pnf' y no 'titulo_pnf'
            ],
            'observacion_formacion_academica' => [
                'nullable', // Es un campo opcional
                'string',
                'max:500', // Un límite razonable para una nota
            ],
        ];
    }

    /**
     * Mensajes personalizados de error en español.
     */
    public function messages(): array
    {
        return [
            'id_titulos.required_without' => 'Debe ingresar un Título Base si no selecciona un Título PNF.',
            'id_titulos.exists'           => 'El Título Base seleccionado no existe en el catálogo.',

            'id_titulos_pnf.required_without' => 'Debe ingresar un Título PNF si no selecciona un Título Base.',
            'id_titulos_pnf.exists'           => 'El Título PNF seleccionado no existe en el catálogo.',

            'observacion_formacion_academica.max' => 'La observación no puede superar los 500 caracteres.',
        ];
    }
}
