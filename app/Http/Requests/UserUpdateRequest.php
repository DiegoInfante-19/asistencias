<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserUpdateRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        // Capturamos el ID del usuario que se está editando desde la ruta
        $id = $this->route('usuario');

        return [
            'username'  => ['required', 'string', 'max:50', 'unique:users,username,' . $id . ',id_users', 'regex:/^[a-zA-Z0-9_]+$/'],
            'name'      => ['required', 'string', 'max:50', 'regex:/^[a-zA-ZñÑáéíóúÁÉÍÓÚ\s]+$/'],
            'last_name' => ['required', 'string', 'max:50', 'regex:/^[a-zA-ZñÑáéíóúÁÉÍÓÚ\s]+$/'],
            // Se ignora el ID actual para evitar falsos positivos en la regla unique
            'cedula'    => ['required', 'string', 'unique:users,cedula,' . $id . ',id_users', 'regex:/^\d{6,8}$/'],
            'email'     => ['required', 'string', 'email:rfc,dns', 'unique:users,email,' . $id . ',id_users', 'regex:/^[a-zA-Z0-9._%+-]{1,64}@[a-zA-Z0-9.-]{1,251}\.com$/'],
            'phone' => ['nullable', 'string', 'unique:users,phone,' . $id . ',id_users', 'regex:/^\d{10,11}$/'],
            'status'    => ['required', 'in:Activo,Inactivo,Suspendido'],
        ];
    }

    public function messages()
    {
        return [
            'required'        => 'Este campo es obligatorio.',
            'username.unique' => 'El nombre de usuario ya está en uso.',
            'email.unique'    => 'El valor del campo email ya está en uso.',
            'cedula.unique'   => 'El valor del campo cedula ya está en uso.',
            'phone.unique'    => 'Este número de teléfono ya está registrado.',
            'email.regex'     => 'Debe ser un correo válido terminado en .com.',
            'name.regex'      => 'Solo letras y espacios permitidos.',
            'last_name.regex' => 'Solo letras y espacios permitidos.',
            'cedula.regex'    => 'Debe tener entre 6 y 8 números.',
            'phone.regex'     => 'Debe tener entre 10 y 11 números sin guiones.',
        ];
    }
}