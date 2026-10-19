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
        
        $esInterno = $this->input('origen_formacion') === 'Interno';

        return [
            'origen_formacion' => [
                'required',
                'string',
                'in:Interno,Externo'
            ],

            // REGLAS PARA TÍTULOS INTERNOS (PNF Institucional)
            'id_titulos_pnf' => [
                $esInterno ? 'required' : 'nullable',
                'nullable',
                'integer',
                'exists:titulos_pnf,id_titulos_pnf',
                // BLINDAJE: No se puede repetir el mismo Título PNF activo para esta persona
                Rule::unique('persona_formacion_academica', 'id_titulos_pnf')
                    ->where('id_personas', $personaId)
                    ->whereNull('deleted_at')
            ],

            // REGLAS PARA TÍTULOS EXTERNOS (Título Base General)
            'id_titulos' => [
                !$esInterno ? 'required' : 'nullable',
                'nullable',
                'integer',
                'exists:titulos,id_titulos'
            ],
            
            // Metadatos específicos para títulos externos
            'mencion_especifica' => [
                'nullable',
                'string',
                'max:150',
                // Evita registrar exactamente la misma mención y título base duplicados para la misma persona
                function ($attribute, $value, $fail) use ($personaId, $esInterno) {
                    if (!$esInterno && $value && $this->input('id_titulos')) {
                        $existe = \App\Models\PersonaFormacionAcademica::where('id_personas', $personaId)
                            ->where('id_titulos', $this->input('id_titulos'))
                            ->where('mencion_especifica', $value)
                            ->whereNull('deleted_at')
                            ->exists();

                        if ($existe) {
                            $fail('Ya posee registrada una formación con este título base y mención específica.');
                        }
                    }
                }
            ],
            
            'institucion_emisora' => [
                'nullable',
                'string',
                'max:200'
            ],

            'anio_egreso' => [
                'nullable',
                'integer',
                'min:1950',
                'max:' . (date('Y') + 1)
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
            'origen_formacion.required' => 'Debe especificar el origen de la formación.',
            'origen_formacion.in'       => 'El origen de formación debe ser Interno o Externo.',

            'id_titulos_pnf.required'   => 'Debe seleccionar un Título PNF válido para formaciones internas.',
            'id_titulos_pnf.exists'     => 'El Título PNF seleccionado no existe en el catálogo institucional.',
            'id_titulos_pnf.unique'     => 'Este Título PNF ya se encuentra registrado en la formación previa de este estudiante.',

            'id_titulos.required'       => 'Debe ingresar un Título Base para formaciones externas.',
            'id_titulos.exists'         => 'El Título Base seleccionado no existe en el catálogo.',

            'mencion_especifica.max'    => 'La mención específica no puede superar los 150 caracteres.',
            'institucion_emisora.max'   => 'La institución emisora no puede superar los 200 caracteres.',
            
            'anio_egreso.integer'       => 'El año de egreso debe ser un número entero.',
            'anio_egreso.min'           => 'El año de egreso no puede ser anterior a 1950.',
            'anio_egreso.max'           => 'El año de egreso no puede ser superior al año actual.',

            'observacion_formacion_academica.max' => 'La observación no puede superar los 500 caracteres.',
        ];
    }
}