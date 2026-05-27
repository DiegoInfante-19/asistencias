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
            'username.regex'     => 'Solo minúsculas y números (sin espacios).',
            'email.regex'        => 'Debe ser un correo válido terminado en .com.',
            'name.regex'         => 'Solo letras y espacios permitidos.',
            'last_name.regex'    => 'Solo letras y espacios permitidos.',
            'cedula.regex'       => 'Debe tener entre 6 y 8 números.',
            'phone.regex'        => 'Debe tener entre 10 y 11 números sin guiones.',
            'phone.unique' => 'Este número de teléfono ya está registrado.',
            'password.regex'     => 'Formato estricto: 8 caracteres (2 mayús, 2 minús, 2 núm, 2 especiales).',
            'password.confirmed' => 'Las contraseñas no coinciden.',
            'required'           => 'Este campo es obligatorio.',
        ];

        return Validator::make($data, [
            'username'  => ['required', 'string', 'min:4', 'max:20', 'unique:users', 'regex:/^[a-z0-9]+$/'],
            'email'     => ['required', 'string', 'email:rfc,dns', 'unique:users', 'regex:/^[a-zA-Z0-9._%+-]{1,64}@[a-zA-Z0-9.-]{1,251}\.com$/'],
            'name'      => ['required', 'string', 'min:3', 'max:50', 'regex:/^[a-zA-ZñÑáéíóúÁÉÍÓÚ\s]+$/'],
            'last_name' => ['required', 'string', 'min:3', 'max:50', 'regex:/^[a-zA-ZñÑáéíóúÁÉÍÓÚ\s]+$/'],
            'cedula'    => ['required', 'string', 'unique:users', 'regex:/^\d{6,8}$/'],
            'phone'     => ['nullable', 'string', 'unique:users', 'regex:/^\d{10,11}$/'],
            'password'  => [
                'required', 'string', 'confirmed',
                'regex:/^(?=(?:[^A-Z]*[A-Z]){2}[^A-Z]*$)(?=(?:[^a-z]*[a-z]){2}[^a-z]*$)(?=(?:[^0-9]*[0-9]){2}[^0-9]*$)(?=(?:[^+*$%&-]*[+*$%&-]){2}[^+*$%&-]*$)[A-Za-z0-9+*$%&-]{8}$/'
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