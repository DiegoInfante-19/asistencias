<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class StoreSesionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // La autorización se delega a la Policy o Control de Roles
    }

    public function rules(): array
    {
        return [
            'id_seccion' => [
                'required',
                'exists:secciones,id_seccion'
            ],
            'id_profesor' => [
                'required',
                'exists:profesores,id_profesor'
            ],
            'fecha_sesion' => [
                'required',
                'date',
                // NOTA: Se removió 'before_or_equal:today' para dar flexibilidad a los encargados 
                // de registrar clases pasadas (hojas de papel), la UI y la lógica de negocio controlan el pasado válido.
                function ($attribute, $value, $fail) {
                    $fecha = Carbon::parse($value);

                    // 1. Restricción de Día de Clase: Estrictamente Miércoles (Carbon::WEDNESDAY = 3)
                    if ($fecha->dayOfWeek !== Carbon::WEDNESDAY) {
                        $fail('Las clases y sesiones solo pueden programarse y realizarse los días miércoles.');
                    }
                },
                function ($attribute, $value, $fail) {
                    // 2. Validación de Feriados y Periodos de Receso con suspensión de actividades
                    $recesoOcupado = DB::table('periodo_recesos')
                        ->where('suspension_actividades', 1)
                        ->whereDate('fecha_inicio_periodo_receso', '<=', $value)
                        ->whereDate('fecha_fin_periodo_receso', '>=', $value)
                        ->first();

                    if ($recesoOcupado) {
                        $fail("El día seleccionado es feriado o periodo sin actividades por el motivo: {$recesoOcupado->nombre_periodo_receso}");
                    }
                },
                function ($attribute, $value, $fail) {
                    // 3. Prevención de Doble Sesión por sección y fecha
                    $idSeccion = $this->input('id_seccion');
                    if ($idSeccion) {
                        $fechaSoloDia = Carbon::parse($value)->toDateString();
                        
                        $sesionDuplicada = DB::table('sesiones')
                            ->where('id_seccion', $idSeccion)
                            ->whereDate('fecha_sesion', '=', $fechaSoloDia)
                            ->whereNull('deleted_at')
                            ->exists();
                            
                        if ($sesionDuplicada) {
                            $fail('Ya existe una sesión de clase registrada para esta sección en la fecha seleccionada.');
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
            'id_seccion.required'    => 'Debe seleccionar una sección académica obligatoria.',
            'id_seccion.exists'      => 'La sección seleccionada no es válida en el sistema.',
            'id_profesor.required'   => 'Debe asignar un profesor responsable de la sesión.',
            'id_profesor.exists'     => 'El profesor seleccionado no es válido en el sistema.',
            'id_profesor.required'   => 'Debe asignar un profesor responsable de la sesión.',
            'fecha_sesion.required'  => 'La fecha y hora de la sesión es obligatoria.',
            'fecha_sesion.date'      => 'El formato de fecha y hora no es válido.',
            'observacion_sesion.max' => 'Las observaciones no pueden exceder los 1000 caracteres.',
        ];
    }
}