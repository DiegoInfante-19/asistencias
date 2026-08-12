<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\DB;

class StoreInscripcionCohorteRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'id_personas' => [
                'required',
                'exists:personas,id_personas',
                // REGLA DE NEGOCIO 1: Impedir doble inscripción activa en el sistema
                function ($attribute, $value, $fail) {
                    $inscripcionActiva = DB::table('inscripcion_cohortes')
                        ->where('id_personas', $value)
                        ->where('estatus_inscripcion_cohortes', 'Activo')
                        ->whereNull('deleted_at')
                        ->exists();

                    if ($inscripcionActiva) {
                        $fail('El participante ya cuenta con una inscripción activa en un grupo académico. Debe retirar la inscripción anterior antes de registrar una nueva.');
                    }
                },
            ],

            'id_grupo' => [
                'required',
                'exists:grupos_academicos,id_grupo',
            ],

            'fecha_inscripcion' => ['required', 'date'],
            'estatus_inscripcion_cohortes' => ['required', 'string', 'in:Activo,Retirado,Finalizado'],
        ];
    }

    /**
     * HOOK DE ARQUITECTURA: Triangulación de Datos (Cross-Check)
     * Se ejecuta tras pasar las validaciones básicas de rules().
     */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            
            // Si las validaciones básicas fallaron, detenemos el análisis profundo
            if ($validator->errors()->has('id_personas') || $validator->errors()->has('id_grupo')) {
                return;
            }

            $idPersona = $this->input('id_personas');
            $idGrupoRequerido = $this->input('id_grupo');

            // 1. Obtenemos el expediente académico base del estudiante (para saber su PNF)
            $expediente = DB::table('titulacion_personas')
                ->where('id_personas', $idPersona)
                ->first();

            // 2. Obtenemos el grupo al que intentan inscribirlo (para saber el PNF del grupo)
            $grupo = DB::table('grupos_academicos')
                ->where('id_grupo', $idGrupoRequerido)
                ->first();

            // REGLA DE NEGOCIO 2: Bloqueo estricto por falta de expediente
            if (!$expediente) {
                $validator->errors()->add(
                    'id_grupo', 
                    'Bloqueo de seguridad (Backend): El estudiante no tiene un expediente académico configurado. Imposible validar la integridad del PNF.'
                );
                return; // Salimos de la comprobación
            }

            // REGLA DE NEGOCIO 3: Inconsistencia Cruzada de PNF
            if ($grupo && $grupo->id_pnf !== $expediente->id_pnf) {
                $validator->errors()->add(
                    'id_grupo', 
                    'Inconsistencia lógica detectada: Intento de inscripción en un grupo ajeno al Programa Nacional de Formación (PNF) asignado en el expediente del estudiante.'
                );
            }
        });
    }

    public function messages(): array
    {
        return [
            'id_personas.required' => 'Debe seleccionar un participante.',
            'id_personas.exists' => 'El participante seleccionado no existe en el sistema.',
            'id_grupo.required' => 'Debe asignar un grupo académico.',
            'id_grupo.exists' => 'El grupo académico seleccionado no es válido.',
            'fecha_inscripcion.required' => 'La fecha de inscripción es obligatoria.',
            'fecha_inscripcion.date' => 'Formato de fecha inválido.',
            'estatus_inscripcion_cohortes.required' => 'El estatus de la inscripción es obligatorio.',
            'estatus_inscripcion_cohortes.in' => 'El estatus debe ser Activo, Retirado o Finalizado.',
        ];
    }
}