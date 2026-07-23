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
                // REGLA DE NEGOCIO CRÍTICA: Impedir doble inscripción activa en el sistema
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

    public function messages(): array
    {
        return [
            'id_personas.required' => 'Debe seleccionar un participante.',
            'id_personas.exists'   => 'El participante seleccionado no existe en el sistema.',
            'id_grupo.required'    => 'Debe asignar un grupo académico.',
            'id_grupo.exists'      => 'El grupo académico seleccionado no es válido.',
            'fecha_inscripcion.required' => 'La fecha de inscripción es obligatoria.',
            'fecha_inscripcion.date'     => 'Formato de fecha inválido.',
            'estatus_inscripcion_cohortes.required' => 'El estatus de la inscripción es obligatorio.',
            'estatus_inscripcion_cohortes.in'       => 'El estatus debe ser Activo, Retirado o Finalizado.',
        ];
    }
}