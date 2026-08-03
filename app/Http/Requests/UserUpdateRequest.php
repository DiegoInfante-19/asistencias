<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\User;

class UserUpdateRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        /** @var \App\Models\User $usuarioAutenticado */
        $usuarioAutenticado = auth()->user();

        // CORRECCIÓN ROUTE MODEL BINDING:
        // Si la ruta inyecta el modelo, lo extraemos. Si inyecta el ID, lo usamos directo.
        $usuarioAEditar = $this->route('usuario');
        $id = $usuarioAEditar instanceof User ? $usuarioAEditar->id_users : $usuarioAEditar;

        // VULNERABILIDAD CRÍTICA CORREGIDA: (Prevención de Degradación de Superiores)
        // Si el usuario a editar es un Administrador, y quien edita NO es Administrador...
        if ($usuarioAEditar instanceof User) {
            $esAdminElObjetivo = (int) $usuarioAEditar->id_rol === User::ROLE_ADMINISTRADOR;
            if ($esAdminElObjetivo && !$usuarioAutenticado->isAdmin()) {
                // Abortamos la petición inmediatamente a nivel de servidor (Ni siquiera evalúa las reglas)
                abort(403, 'Brecha de seguridad: Un Coordinador no tiene autorización para editar el perfil de un Administrador.');
            }
        }

        return [
            'username'        => ['required', 'string', 'max:20', 'unique:users,username,' . $id . ',id_users', 'regex:/^[A-Z](?=.*\d)[a-zA-Z0-9_]{3,19}$/'],
            'name_users'      => ['required', 'string', 'max:50', 'regex:/^[a-zA-ZñÑáéíóúÁÉÍÓÚ\s]{3,50}$/'],
            'last_name_users' => ['required', 'string', 'max:50', 'regex:/^[a-zA-ZñÑáéíóúÁÉÍÓÚ\s]{3,50}$/'],
            'cedula_users'    => ['required', 'string', 'unique:users,cedula_users,' . $id . ',id_users', 'regex:/^\d{6,8}$/'],
            'email_users'     => ['required', 'string', 'email:rfc,dns', 'unique:users,email_users,' . $id . ',id_users', 'regex:/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/'],
            'phone_users'     => ['nullable', 'string', 'unique:users,phone_users,' . $id . ',id_users', 'regex:/^\d{10,11}$/'],
            'status_users'    => ['required', 'in:Activo,Inactivo,Suspendido'],
            
            'id_rol'          => [
                'required', 
                'exists:roles,id_rol',
                function ($attribute, $value, $fail) use ($usuarioAutenticado) {
                    // Prevención de Escalamiento (Un Coordinador no puede volver a alguien Administrador)
                    if ($usuarioAutenticado && $usuarioAutenticado->isCoordinador() && (int) $value === User::ROLE_ADMINISTRADOR) {
                        $fail('No tienes permisos para asignar el rol de Administrador.');
                    }
                }
            ],
        ];
    }

    public function messages()
    {
        return [
            'required'               => 'Este campo es obligatorio.',
            'id_rol.exists'          => 'El rol seleccionado no es válido.',
            'email_users.unique'     => 'Este correo electrónico ya está registrado.',
            'cedula_users.unique'    => 'Esta cédula ya está registrada.',
            'phone_users.unique'     => 'Este número de teléfono ya está registrado.',
            'username.unique'        => 'Este nombre de usuario ya está en uso.',
            'username.regex'         => 'Debe iniciar con mayúscula, tener al menos un número y entre 4-20 caracteres. Sin espacios.',
            'email_users.regex'      => 'Ingrese un correo electrónico válido.',
            'name_users.regex'       => 'Solo letras y espacios (mínimo 3 caracteres).',
            'last_name_users.regex'  => 'Solo letras y espacios (mínimo 3 caracteres).',
            'cedula_users.regex'     => 'La cédula debe tener entre 6 y 8 números exactos. Sin espacios.',
            'phone_users.regex'      => 'El teléfono debe tener entre 10 y 11 números, sin guiones ni espacios.',
        ];
    }
}