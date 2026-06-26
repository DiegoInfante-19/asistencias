<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\ValidarUsername;
use App\Rules\ValidarNombrePropio;
use App\Rules\ValidarCedula;
use App\Rules\ValidarEmail;
use App\Rules\ValidarTelefono;

class UserUpdateRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $id = $this->route('usuario');

        return [
            'username'        => ['required', 'string', 'max:20', 'unique:users,username,' . $id . ',id_users', new ValidarUsername()],
            'name_users'      => ['required', 'string', 'max:50', new ValidarNombrePropio()],
            'last_name_users' => ['required', 'string', 'max:50', new ValidarApellidoPropio()],
            'cedula_users'    => ['required', 'string', 'unique:users,cedula_users,' . $id . ',id_users', new ValidarCedula()],
            'email_users'     => ['required', 'string', 'email:rfc,dns', 'unique:users,email_users,' . $id . ',id_users', new ValidarEmail()],
            'phone_users'     => ['nullable', 'string', 'unique:users,phone_users,' . $id . ',id_users', new ValidarTelefono()],
            'status_users'    => ['required', 'in:Activo,Inactivo,Suspendido'],
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
        ];
    }
}