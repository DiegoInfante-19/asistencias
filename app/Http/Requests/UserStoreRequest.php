<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\ValidarUsername;
use App\Rules\ValidarNombrePropio;
use App\Rules\ValidarCedula;
use App\Rules\ValidarEmail;
use App\Rules\ValidarTelefono;
use App\Rules\ValidarPasswordFuerte;

class UserStoreRequest extends FormRequest{
    public function authorize(){
        return true; 
    }

    public function rules(){
        return [
            'name_users'      => ['required', 'string', 'max:50', new ValidarNombrePropio()],
            'last_name_users' => ['required', 'string', 'max:50', new ValidarNombrePropio()],
            'cedula_users'    => ['required', 'string', 'unique:users,cedula_users', new ValidarCedula()],
            'email_users'     => ['required', 'string', 'email:rfc,dns', 'unique:users,email_users', new ValidarEmail()],
            'username'        => ['required', 'string', 'max:20', 'unique:users,username', new ValidarUsername()],
            'phone_users'     => ['nullable', 'string', 'unique:users,phone_users', new ValidarTelefono()],
            'id_rol'          => ['required', 'exists:roles,id_rol'],
            'password'        => ['required', 'string', 'confirmed', new ValidarPasswordFuerte()],
        ];
    }

    public function messages(){
        return [
            'required'               => 'Este campo es obligatorio.',
            'email_users.unique'     => 'Este correo electrónico ya está registrado.',
            'cedula_users.unique'    => 'Esta cédula ya está registrada.',
            'phone_users.unique'     => 'Este número de teléfono ya está registrado.',
            'username.unique'        => 'Este nombre de usuario ya está en uso.',
            'password.confirmed'     => 'Las contraseñas no coinciden.',
            'id_rol.exists'          => 'El rol seleccionado no es válido.',
        ];
    }
    
}