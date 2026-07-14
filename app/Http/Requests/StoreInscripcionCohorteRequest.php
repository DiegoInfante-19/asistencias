<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreInscripcionCohorteRequest extends FormRequest
{
    /**
     * Determina si el usuario está autorizado para hacer esta petición.
     */
    public function authorize(): bool
    {
        return true; 
    }

    /**
     * Reglas de validación que se aplicarán a la petición.
     */
    public function rules(): array
    {
        return [
            'id_cohortes' => [
                'required',
                'integer',
                // Verifica que la cohorte seleccionada exista en la base de datos
                'exists:cohortes,id_cohortes',
            ],
            'fecha_inscripcion' => [
                'required',
                'date', // Valida que sea una fecha válida (ej. YYYY-MM-DD)
            ],
            'estatus_inscripcion_cohortes' => [
                'required',
                'string',
                'max:50',
                // Opcional pero recomendado: Restringir a valores exactos si es un campo de texto
                // 'in:Activo,Retirado,Suspendido' 
            ],
        ];
    }

    /**
     * Mensajes personalizados de error en español.
     */
    public function messages(): array
    {
        return [
            'id_cohortes.required' => 'Debe seleccionar una cohorte.',
            'id_cohortes.integer'  => 'El identificador de la cohorte no es válido.',
            'id_cohortes.exists'   => 'La cohorte seleccionada no se encuentra registrada en el sistema.',
            
            'fecha_inscripcion.required' => 'La fecha de inscripción es obligatoria.',
            'fecha_inscripcion.date'     => 'La fecha de inscripción no tiene un formato válido.',

            'estatus_inscripcion_cohortes.required' => 'Debe asignar un estatus a la inscripción.',
            'estatus_inscripcion_cohortes.string'   => 'El estatus debe ser una cadena de texto.',
            'estatus_inscripcion_cohortes.max'      => 'El estatus no puede superar los 50 caracteres.',
        ];
    }
}
