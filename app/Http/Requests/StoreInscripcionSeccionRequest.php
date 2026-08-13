<?php
namespace App\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\DB;

class StoreInscripcionSeccionRequest extends FormRequest
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
                // REGLA 1: Impedir doble inscripción activa en el sistema
                function ($attribute, $value, $fail) {
                    $inscripcionActiva = DB::table('inscripciones_secciones')
                        ->where('id_personas', $value)
                        ->where('estatus_inscripcion', 'Activo')
                        ->whereNull('deleted_at')
                        ->exists();
                    if ($inscripcionActiva) {
                        $fail('El participante ya cuenta con una inscripción activa en una sección. Debe retirar la inscripción anterior antes de registrar una nueva.');
                    }
                },
            ],
            // ACTUALIZADO: Validamos contra la nueva tabla 'secciones'
            'id_seccion' => [
                'required',
                'integer',
                'exists:secciones,id_seccion',
            ],
            'fecha_inscripcion'   => ['required', 'date'],
            'estatus_inscripcion' => ['required', 'string', 'in:Activo,Retirado,Finalizado'],
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            if ($validator->errors()->has('id_personas') || $validator->errors()->has('id_seccion')) {
                return;
            }

            $idPersona = $this->input('id_personas');
            $idSeccionRequerida = $this->input('id_seccion');

            // 1. Expediente base del estudiante (PNF)
            $expediente = DB::table('titulacion_personas')
                ->where('id_personas', $idPersona)
                ->first();

            // 2. Sección destino (PNF)
            $seccion = DB::table('secciones')
                ->where('id_seccion', $idSeccionRequerida)
                ->first();

            if (!$expediente) {
                $validator->errors()->add(
                    'id_seccion',
                    'Bloqueo de seguridad: El estudiante no tiene un expediente académico configurado.'
                );
                return;
            }

            // REGLA CRUZADA: Coherencia de PNF entre el expediente y la sección mixta
            if ($seccion && $seccion->id_pnf !== $expediente->id_pnf) {
                $validator->errors()->add(
                    'id_seccion',
                    'Inconsistencia lógica: Intento de inscripción en una sección cuyo PNF no coincide con el expediente del estudiante.'
                );
            }
        });
    }

    public function messages(): array
    {
        return [
            'id_personas.required'   => 'Debe seleccionar un participante.',
            'id_personas.exists'      => 'El participante seleccionado no existe en el sistema.',
            'id_seccion.required'      => 'Debe seleccionar una sección académica.',
            'id_seccion.exists'         => 'La sección seleccionada no es válida.',
            'fecha_inscripcion.required' => 'La fecha de inscripción es obligatoria.',
            'fecha_inscripcion.date'      => 'Formato de fecha inválido.',
            'estatus_inscripcion.required' => 'El estatus de la inscripción es obligatorio.',
            'estatus_inscripcion.in'        => 'El estatus debe ser Activo, Retirado o Finalizado.',
        ];
    }
}