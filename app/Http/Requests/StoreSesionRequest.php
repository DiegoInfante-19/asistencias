<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\DB;

class StoreSesionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; 
    }

    public function rules(): array
    {
        return [
            'id_grupo' => [
                'required',
                'exists:grupos_academicos,id_grupo'
            ],
            'fecha_sesion' => [
                'required',
                'date',
                'before_or_equal:today', // Regla 1: No permitir fechas futuras
                function ($attribute, $value, $fail) {
                    // Regla 2: Validar nativamente que sea Miércoles (3)
                    $numeroDia = date('N', strtotime($value));

                    if ($numeroDia != 3) {
                        $fail('Las clases solo pueden aperturarse los días miércoles.');
                    }
                },
                function ($attribute, $value, $fail) {
                    // Regla 3: Validar que la fecha NO caiga dentro de un período de receso
                    $recesoOcupado = DB::table('periodo_recesos')
                        ->where('suspension_actividades', 1)
                        ->whereDate('fecha_inicio_periodo_receso', '<=', $value)
                        ->whereDate('fecha_fin_periodo_receso', '>=', $value)
                        ->first();

                    if ($recesoOcupado) {
                        $fail("El día seleccionado es feriado por el motivo: {$recesoOcupado->nombre_periodo_receso}");
                    }
                },
                function ($attribute, $value, $fail) {
                    // Regla 4: PREVENCIÓN DE DOBLE SESIÓN (Unicidad compuesta por día)
                    $idGrupo = $this->input('id_grupo');
                    
                    if ($idGrupo) {
                        $sesionDuplicada = DB::table('sesiones')
                            ->where('id_grupo', $idGrupo)
                            // Usamos whereDate para extraer solo la fecha (Y-m-d) ignorando las horas
                            ->whereDate('fecha_sesion', '=', date('Y-m-d', strtotime($value)))
                            ->whereNull('deleted_at') // Fundamental: ignorar sesiones eliminadas (SoftDeletes)
                            ->exists();

                        if ($sesionDuplicada) {
                            $fail('Ya existe una sesión de clase registrada para este grupo en la fecha seleccionada.');
                        }
                    }
                }
            ],
            'observacion_sesion' => [
                'nullable',
                'string',
                'max:1000'
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'id_grupo.required'             => 'Debe seleccionar un grupo académico obligatorio.',
            'id_grupo.exists'               => 'El grupo seleccionado no es válido en el sistema.',
            'fecha_sesion.required'         => 'La fecha de la sesión es obligatoria.',
            'fecha_sesion.date'             => 'El formato de fecha no es válido.',
            'fecha_sesion.before_or_equal'  => 'No se pueden aperturar clases con fechas futuras.',
            'observacion_sesion.max'        => 'Las observaciones no pueden exceder los 1000 caracteres.',
        ];
    }
}