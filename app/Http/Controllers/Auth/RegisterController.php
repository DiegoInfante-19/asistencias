<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

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
        // 1. Mensajes actualizados para que coincidan con las nuevas reglas
        $mensajes = [
            'username.regex'     => 'Debe iniciar con mayúscula, tener al menos un número y entre 4-20 caracteres.',
            'email.regex'        => 'Ingrese un correo electrónico válido.',
            'name.regex'         => 'Solo letras y espacios permitidos (mínimo 3 caracteres).',
            'last_name.regex'    => 'Solo letras y espacios permitidos (mínimo 3 caracteres).',
            'cedula.regex'       => 'Debe tener entre 6 y 8 números.',
            'phone.regex'        => 'Debe tener entre 10 y 11 números sin guiones.',
            'phone.unique'       => 'Este número de teléfono ya está registrado.',
            'password.regex'     => 'Mínimo 8 caracteres: al menos una mayúscula, una minúscula, un número y un símbolo.',
            'password.confirmed' => 'Las contraseñas no coinciden.',
            'required'           => 'Este campo es obligatorio.',
            'username.unique'    => 'Este nombre de usuario ya está en uso.',
            'email.unique'       => 'Este correo electrónico ya está registrado.',
            'cedula.unique'      => 'Esta cédula ya está registrada.',
        ];

        // 2. Reglas sincronizadas con el FormRequest y el JS
        return Validator::make($data, [
            'username'  => ['required', 'string', 'min:4', 'max:20', 'unique:users,username', 'regex:/^[A-Z](?=.*\d)[a-zA-Z0-9_]{3,19}$/'],
            'email'     => ['required', 'string', 'email:rfc,dns', 'unique:users,email_users', 'regex:/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/'],
            'name'      => ['required', 'string', 'min:3', 'max:50', 'regex:/^[a-zA-ZñÑáéíóúÁÉÍÓÚ\s]{3,50}$/'],
            'last_name' => ['required', 'string', 'min:3', 'max:50', 'regex:/^[a-zA-ZñÑáéíóúÁÉÍÓÚ\s]{3,50}$/'],
            'cedula'    => ['required', 'string', 'unique:users,cedula_users', 'regex:/^\d{6,8}$/'],
            'phone'     => ['nullable', 'string', 'unique:users,phone_users', 'regex:/^\d{10,11}$/'],
            'password'  => [
                'required', 'string', 'confirmed',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&.+-])[A-Za-z\d@$!%*?&.+-]{8,64}$/'
            ],
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