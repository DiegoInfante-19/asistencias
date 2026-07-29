<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\DB;

class StoreSesionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; 
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
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
            ],
            // CORREGIDO: Cambiado a singular para que coincida con la columna y la vista
            'observacion_sesion' => [
                'nullable',
                'string',
                'max:1000'
            ],
        ];
    }

    /**
     * Mensajes de error estándar para las reglas nativas.
     */
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