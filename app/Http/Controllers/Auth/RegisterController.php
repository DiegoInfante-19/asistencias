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
        $mensajes = [
            'username.regex'     => 'El nombre de usuario solo puede contener letras minúsculas y números, sin espacios (4-20 caracteres).',
            'username.min'       => 'El nombre de usuario debe tener al menos 4 caracteres.',
            'email.email'        => 'Debe ingresar una dirección de correo válida que incluya @ y un dominio (ejemplo@correo.com).',
            'name.regex'         => 'Los nombres solo pueden contener letras y espacios (mínimo 3 caracteres).',
            'last_name.regex'    => 'Los apellidos solo pueden contener letras y espacios (mínimo 3 caracteres).',
            'cedula.regex'       => 'La cédula debe contener solo números y tener entre 7 y 8 dígitos.',
            'phone.regex'        => 'El teléfono debe seguir el formato 04XX-1234567.',
            'password.regex'     => 'La contraseña es débil. Debe incluir: 2 mayúsculas, 2 minúsculas, 2 números y 1 carácter especial (*,$,%,&,+,-).',
            'password.min'       => 'La contraseña debe tener al menos 8 caracteres.',
            'password.confirmed' => 'La confirmación de la contraseña no coincide.',
            'required'           => 'Este campo es obligatorio y no puede quedar vacío.',
        ];

        return Validator::make($data, [
            // Username: Solo minúsculas y números, 4 a 20 caracteres
            'username'  => ['required', 'string', 'min:4', 'max:20', 'unique:users', 'regex:/^[a-z0-9]+$/'],

            // Email: RFC y DNS check para asegurar que el dominio existe y tiene formato .com, .net, etc.
            'email'     => ['required', 'string', 'email:rfc,dns', 'max:255', 'unique:users'],

            // Nombres y Apellidos: Solo letras y espacios, min 3
            'name'      => ['required', 'string', 'min:3', 'max:50', 'regex:/^[a-zA-ZñÑáéíóúÁÉÍÓÚ\s]+$/'],
            'last_name' => ['required', 'string', 'min:3', 'max:50', 'regex:/^[a-zA-ZñÑáéíóúÁÉÍÓÚ\s]+$/'],

            // Cédula: Solo números, longitud 7 u 8
            'cedula'    => ['required', 'string', 'unique:users', 'regex:/^\d{7,8}$/'],

            // Teléfono: Sigue siendo opcional en la base de datos, pero si se llena, debe cumplir el formato
            'phone'     => ['nullable', 'regex:/^\d{4}-\d{7}$/'],

            // Password: La expresión regular ahora busca exactamente lo que pediste
            'password'  => [
                'required',
                'string',
                'min:8',
                'confirmed',
                // 2 mayúsculas, 2 minúsculas, 2 números, 1 especial de la lista
                'regex:/^(?=(.*[A-Z]){2,})(?=(.*[a-z]){2,})(?=(.*[0-9]){2,})(?=(.*[*$%&+-]){1,}).+$/'
            ],
        ], $mensajes);
    }

    protected function create(array $data)
    {
        return User::create([
            'username'  => $data['username'],
            'email'     => $data['email'],
            'name'      => $data['name'],
            'last_name' => $data['last_name'],
            'cedula'    => $data['cedula'],
            'phone'     => $data['phone'] ?? null, // Captura el teléfono
            'status'    => 'Activo',
            'role_id'   => 3, // Rol de Profesor por defecto
            'password'  => Hash::make($data['password']),
        ]);
    }
}