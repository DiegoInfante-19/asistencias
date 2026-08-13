<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;
use App\Enums\NivelAcademico;

class StoreProfesorRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; 
    }

    public function rules(): array
    {
        return [
            'id_users'                  => ['required', 'exists:users,id_users'],
            'id_pnf'                    => ['required', 'exists:pnfs,id_pnf'],
            'nivel_asignado'            => ['required', new Enum(NivelAcademico::class)],
            'fecha_asignacion_profesor' => ['required', 'date'],
            
            // Opcional por si en la vista envías las secciones a sincronizar de una vez
            'secciones'                 => ['nullable', 'array'],
            'secciones.*'               => ['integer', 'exists:secciones,id_seccion'],
        ];
    }

    public function messages(): array
    {
        return [
            'id_users.required'                  => 'Debe seleccionar un usuario para asignarle el perfil de profesor.',
            'id_users.exists'                    => 'El usuario seleccionado no existe en el sistema.',
            'id_pnf.required'                    => 'Debe asignar un PNF al profesor.',
            'id_pnf.exists'                      => 'El PNF seleccionado no es válido.',
            'nivel_asignado.required'            => 'El nivel académico asignado es obligatorio.',
            'nivel_asignado.enum'                => 'El nivel académico seleccionado debe ser TSU o Ingeniería.',
            'fecha_asignacion_profesor.required' => 'La fecha de asignación es obligatoria.',
            'fecha_asignacion_profesor.date'     => 'La fecha de asignación debe ser una fecha válida.',
            'secciones.*.exists'                 => 'Una o más de las secciones seleccionadas no existen en el sistema.',
        ];
    }
}