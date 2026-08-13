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
            'id_seccion' => [
                'required',
                'exists:secciones,id_seccion'
            ],
            // AGREGADO: Validación obligatoria del profesor responsable de la sesión
            'id_profesor' => [
                'required',
                'exists:profesores,id_profesor'
            ],
            'fecha_sesion' => [
                'required',
                'date', // Valida que sea una fecha/hora válida para un campo dateTime
                'before_or_equal:today', 
                function ($attribute, $value, $fail) {
                    $numeroDia = date('N', strtotime($value));
                    if ($numeroDia != 3) {
                        $fail('Las clases solo pueden aperturarse los días miércoles.');
                    }
                },
                function ($attribute, $value, $fail) {
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
                    // PREVENCIÓN DE DOBLE SESIÓN alineada a la migración dateTime
                    $idSeccion = $this->input('id_seccion');
                    if ($idSeccion) {
                        $fechaSoloDia = date('Y-m-d', strtotime($value));
                        
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
            'id_seccion.required'          => 'Debe seleccionar una sección académica obligatoria.',
            'id_seccion.exists'            => 'La sección seleccionada no es válida en el sistema.',
            'id_profesor.required'         => 'Debe asignar un profesor responsable de la sesión.',
            'id_profesor.exists'           => 'El profesor seleccionado no es válido en el sistema.',
            'fecha_sesion.required'        => 'La fecha y hora de la sesión es obligatoria.',
            'fecha_sesion.date'            => 'El formato de fecha y hora no es válido.',
            'fecha_sesion.before_or_equal' => 'No se pueden aperturar clases con fechas futuras.',
            'observacion_sesion.max'       => 'Las observaciones no pueden exceder los 1000 caracteres.',
        ];
    }
}