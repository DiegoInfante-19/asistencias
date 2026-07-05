<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AsignarPnfRequest extends FormRequest
{
    /**
     * Determina si el usuario está autorizado a realizar esta petición.
     */
    public function authorize(): bool
    {
        // Cambiar a true para permitir que los usuarios usen este validador
        return true;
    }

    /**
     * Obtiene las reglas de validación que se aplicarán a la petición.
     */
    public function rules(): array
    {
        return [
            // Paso 2.2: id_pnf obligatorio y debe existir en la llave primaria de la tabla pnfs
            'id_pnf' => [
                'required',
                'exists:pnfs,id_pnf'
            ],
            
            // Paso 2.2: fecha_asignacion_profesor obligatoria y con formato de fecha válido
            'fecha_asignacion_profesor' => [
                'required',
                'date'
            ],
        ];
    }

    /**
     * Personaliza los mensajes de error para mostrarlos de forma amistosa en la vista.
     */
    public function messages(): array
    {
        return [
            'id_pnf.required' => 'Debe seleccionar un Programa Nacional de Formación (PNF).',
            'id_pnf.exists'   => 'El PNF seleccionado no es válido o no está registrado en el sistema.',
            
            'fecha_asignacion_profesor.required' => 'La fecha de asignación académica es obligatoria.',
            'fecha_asignacion_profesor.date'     => 'El formato de la fecha de asignación no es válido.',
        ];
    }
}