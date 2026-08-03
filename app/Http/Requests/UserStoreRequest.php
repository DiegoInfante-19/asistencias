<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\User;

class UserStoreRequest extends FormRequest
{
    public function authorize()
    {
        return true; 
    }

    public function rules()
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();

        return [
            'name_users'      => ['required', 'string', 'max:50', 'regex:/^[a-zA-ZñÑáéíóúÁÉÍÓÚ\s]{3,50}$/'],
            'last_name_users' => ['required', 'string', 'max:50', 'regex:/^[a-zA-ZñÑáéíóúÁÉÍÓÚ\s]{3,50}$/'],
            'cedula_users'    => ['required', 'string', 'unique:users,cedula_users', 'regex:/^\d{6,8}$/'],
            'email_users'     => ['required', 'string', 'email:rfc,dns', 'unique:users,email_users', 'regex:/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/'],
            'username'        => ['required', 'string', 'max:20', 'unique:users,username', 'regex:/^[A-Z](?=.*\d)[a-zA-Z0-9_]{3,19}$/'],
            'phone_users'     => ['nullable', 'string', 'unique:users,phone_users', 'regex:/^\d{10,11}$/'],
            
            'id_rol'          => [
                'required', 
                'exists:roles,id_rol',
                function ($attribute, $value, $fail) use ($user) {
                    if ($user && $user->isCoordinador() && (int) $value === User::ROLE_ADMINISTRADOR) {
                        $fail('No tienes permisos para crear usuarios con el rol de Administrador.');
                    }
                }
            ],

            'password'        => [
                'required', 'string', 'confirmed', 
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&.+-])[A-Za-z\d@$!%*?&.+-]{8,64}$/'
            ],
        ];
    }

    public function messages()
    {
        return [
            'required'               => 'Este campo es obligatorio.',
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
            'password.regex'         => 'Mínimo 8 caracteres: al menos una mayúscula, una minúscula, un número y un símbolo.',
            'password.confirmed'     => 'Las contraseñas no coinciden.',
            'id_rol.exists'          => 'El rol seleccionado no es válido.',
        ];
    }
}