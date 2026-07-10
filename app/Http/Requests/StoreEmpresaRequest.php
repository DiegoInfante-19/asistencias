<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreEmpresaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Saneamiento preventivo: convierte a mayúsculas antes de pasar por las reglas
     */
    protected function prepareForValidation(): void
    {
        if ($this->has('nombre_empresa')) {
            $this->merge([
                // MB_STRTOUPPER permite convertir caracteres especiales (ñ, acentos) correctamente
                'nombre_empresa' => mb_strtoupper(trim($this->nombre_empresa), 'UTF-8'),
            ]);
        }
    }

    public function rules(): array
    {
        return [
            'nombre_empresa' => [
                'required',
                'string',
                'max:150',
                'unique:empresas,nombre_empresa',
                // Actualizado: Bloquea letras minúsculas
                'regex:/^[A-Z0-9ÁÉÍÓÚÑ\s.,&\-]+$/' 
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'nombre_empresa.required' => 'El nombre de la empresa es obligatorio.',
            'nombre_empresa.string'   => 'El formato del nombre no es válido.',
            'nombre_empresa.max'      => 'El nombre no puede exceder los 150 caracteres.',
            'nombre_empresa.unique'   => 'Esta empresa ya se encuentra registrada en el sistema.',
            'nombre_empresa.regex'    => 'El nombre de la empresa debe contener únicamente letras mayúsculas, números, espacios y los símbolos . , & -.',
        ];
    }
}




// namespace App\Http\Requests;

// use Illuminate\Foundation\Http\FormRequest;

// class StoreEmpresaRequest extends FormRequest
// {
//     public function authorize(): bool
//     {
//         return true;
//     }

//     public function rules(): array
//     {
//         return [
//             'nombre_empresa' => [
//                 'required',
//                 'string',
//                 'max:150',
//                 'unique:empresas,nombre_empresa',
//                 'regex:/^[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ\s.,&\-]+$/'
//             ],
//         ];
//     }

//     public function messages(): array
//     {
//         return [
//             'nombre_empresa.required' => 'El nombre de la empresa es obligatorio.',
//             'nombre_empresa.string'   => 'El formato del nombre no es válido.',
//             'nombre_empresa.max'      => 'El nombre no puede exceder los 150 caracteres.',
//             'nombre_empresa.unique'   => 'Esta empresa ya se encuentra registrada en el sistema.',
//             'nombre_empresa.regex'    => 'El nombre contiene caracteres no permitidos (solo letras, números, espacios y los símbolos . , & -).',
//         ];
//     }
// } 