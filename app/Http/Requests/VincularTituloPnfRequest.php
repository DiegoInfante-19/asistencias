<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class VincularTituloPnfRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            // Verificamos que el título base seleccionado realmente exista en el catálogo
            'id_titulo' => [
                'required', 
                'exists:titulos,id_titulos'
            ],
            // Validamos el texto ingresado a mano
            'nombre_titulo_pnf' => [
                'required', 
                'string', 
                'max:150',
                'regex:/^[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ\s.,\-]+$/'
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'id_titulo.required' => 'Debe seleccionar un título base del catálogo.',
            'id_titulo.exists'   => 'El título seleccionado no es válido.',
            'nombre_titulo_pnf.required' => 'El nombre específico del título es obligatorio.',
            'nombre_titulo_pnf.max'      => 'El nombre no debe exceder los 150 caracteres.',
            'nombre_titulo_pnf.regex'    => 'El formato del nombre contiene caracteres no permitidos.',
        ];
    }
}