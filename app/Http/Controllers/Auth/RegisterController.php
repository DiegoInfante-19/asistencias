<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
// Importamos nuestros nuevos guardias
use App\Rules\ValidarUsername;
use App\Rules\ValidarNombrePropio;
use App\Rules\ValidarApellidoPropio;
use App\Rules\ValidarCedula;
use App\Rules\ValidarEmail;
use App\Rules\ValidarTelefono;
use App\Rules\ValidarPasswordFuerte;

class RegisterController extends Controller
{
    use RegistersUsers;

    protected $redirectTo = '/';

    public function __construct()
    {
        $this->middleware('guest');
    }

    protected function validator(array $data)
    {
        // Solo conservamos los mensajes de base de datos y confirmación
        $mensajes = [
            'phone.unique'       => 'Este número de teléfono ya está registrado.',
            'password.confirmed' => 'Las contraseñas no coinciden.',
            'required'           => 'Este campo es obligatorio.',
            'username.unique'    => 'Este nombre de usuario ya está en uso.',
            'email.unique'       => 'Este correo electrónico ya está registrado.',
            'cedula.unique'      => 'Esta cédula ya está registrada.',
        ];

        return Validator::make($data, [
            'username'  => ['required', 'string', 'min:4', 'max:20', 'unique:users,username', new ValidarUsername()],
            'email'     => ['required', 'string', 'email:rfc,dns', 'unique:users,email_users', new ValidarEmail()],
            'name'      => ['required', 'string', 'min:3', 'max:50', new ValidarNombrePropio()],
            'last_name' => ['required', 'string', 'min:3', 'max:50', new ValidarApellidoPropio()],
            'cedula'    => ['required', 'string', 'unique:users,cedula_users', new ValidarCedula()],
            'phone'     => ['nullable', 'string', 'unique:users,phone_users', new ValidarTelefono()],
            'password'  => ['required', 'string', 'confirmed', new ValidarPasswordFuerte()],
        ], $mensajes);
    }

    protected function create(array $data)
    {
        return User::create([
            'username'        => $data['username'],
            'email_users'     => $data['email'],
            'name_users'      => $data['name'],
            'last_name_users' => $data['last_name'],
            'cedula_users'    => $data['cedula'],
            'phone_users'     => $data['phone'] ?? null, 
            'status_users'    => 'Activo',
            'id_rol'          => 3, 
            'password_users'  => Hash::make($data['password']),
        ]);
    }
}