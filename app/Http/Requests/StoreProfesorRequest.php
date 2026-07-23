<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;
use App\Enums\NivelAcademico;

class StoreProfesorRequest extends FormRequest
{
    /**
     * Determina si el usuario está autorizado para realizar esta petición.
     */
    public function authorize(): bool
    {
        return true; // Ajustar según tus políticas de Roles/Spatie
    }

    /**
     * Obtiene las reglas de validación aplicables a la petición.
     */
    public function rules(): array
    {
        return [
            'id_users' => ['required', 'exists:users,id_users'],
            'id_pnf'   => ['required', 'exists:pnfs,id_pnf'],
            
            // VALIDACIÓN DEL ENUM: Exige que el nivel exista dentro de NivelAcademico
            'nivel_asignado' => ['required', new Enum(NivelAcademico::class)],
            
            'fecha_asignacion_profesor' => ['required', 'date'],
        ];
    }

    /**
     * Mensajes de error personalizados para la vista.
     */
    public function messages(): array
    {
        return [
            'id_users.required'       => 'Debe seleccionar un usuario para asignarle el perfil de profesor.',
            'id_users.exists'         => 'El usuario seleccionado no existe en el sistema.',
            'id_pnf.required'         => 'Debe asignar un PNF al profesor.',
            'id_pnf.exists'           => 'El PNF seleccionado no es válido.',
            'nivel_asignado.required' => 'El nivel académico asignado es obligatorio.',
            'nivel_asignado.enum'     => 'El nivel académico seleccionado debe ser TSU o Ingeniería.',
            'fecha_asignacion_profesor.required' => 'La fecha de asignación es obligatoria.',
            'fecha_asignacion_profesor.date'     => 'La fecha de asignación debe ser una fecha válida.',
        ];
    }
}