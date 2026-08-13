<?php
namespace App\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;

class StorePersonaFormacionAcademicaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'id_titulos' => [
                'nullable',
                'required_without:id_titulos_pnf',
                'integer',
                'exists:titulos,id_titulos',
            ],
            'id_titulos_pnf' => [
                'nullable',
                'required_without:id_titulos',
                'integer',
                'exists:titulos_pnf,id_titulos_pnf',
            ],
            // NUEVA REGLA: Origen de la formación obligatorio y validado por dominio
            'origen_formacion' => [
                'required',
                'string',
                'in:Interno,Externo'
            ],
            'observacion_formacion_academica' => [
                'nullable',
                'string',
                'max:500',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'id_titulos.required_without' => 'Debe ingresar un Título Base si no selecciona un Título PNF.',
            'id_titulos.exists'           => 'El Título Base seleccionado no existe en el catálogo.',
            'id_titulos_pnf.required_without' => 'Debe ingresar un Título PNF si no selecciona un Título Base.',
            'id_titulos_pnf.exists'       => 'El Título PNF seleccionado no existe en el catálogo.',
            'origen_formacion.required'   => 'Debe especificar el origen de la formación.',
            'origen_formacion.in'         => 'El origen de formación debe ser Interno o Externo.',
            'observacion_formacion_academica.max' => 'La observación no puede superar los 500 caracteres.',
        ];
    }
}