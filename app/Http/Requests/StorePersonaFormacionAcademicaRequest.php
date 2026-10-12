<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StorePersonaFormacionAcademicaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        // Obtenemos la persona desde la ruta (ej: /personas/{persona}/formacion)
        $persona = $this->route('persona');
        $personaId = $persona ? $persona->id_personas : null;

        return [
            'id_titulos' => [
                'nullable',
                'required_without:id_titulos_pnf',
                'integer',
                'exists:titulos,id_titulos',
                // BLINDAJE: No se puede repetir el mismo Título Base para esta persona
                Rule::unique('persona_formacion_academica', 'id_titulos')
                    ->where('id_personas', $personaId)
                    ->whereNull('deleted_at')
            ],
            'id_titulos_pnf' => [
                'nullable',
                'required_without:id_titulos',
                'integer',
                'exists:titulos_pnf,id_titulos_pnf',
                // BLINDAJE: No se puede repetir el mismo Título PNF para esta persona
                Rule::unique('persona_formacion_academica', 'id_titulos_pnf')
                    ->where('id_personas', $personaId)
                    ->whereNull('deleted_at')
            ],
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
            'id_titulos.unique'           => 'Este Título Base ya se encuentra registrado en la formación previa de este estudiante.',
            
            'id_titulos_pnf.required_without' => 'Debe ingresar un Título PNF si no selecciona un Título Base.',
            'id_titulos_pnf.exists'           => 'El Título PNF seleccionado no existe en el catálogo.',
            'id_titulos_pnf.unique'           => 'Este Título PNF ya se encuentra registrado en la formación previa de este estudiante.',
            
            'origen_formacion.required'   => 'Debe especificar el origen de la formación.',
            'origen_formacion.in'         => 'El origen de formación debe ser Interno o Externo.',
            'observacion_formacion_academica.max' => 'La observación no puede superar los 500 caracteres.',
        ];
    }
}