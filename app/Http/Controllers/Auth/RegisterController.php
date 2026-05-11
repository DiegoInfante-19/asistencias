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

    protected function validator(array $data){
        return Validator::make($data, [
            'username'  => ['required', 'string', 'max:50', 'unique:users'],
            'email'     => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'name'      => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'cedula'    => ['required', 'string', 'max:20', 'unique:users'],
            'phone'     => ['nullable', 'string', 'max:20'], // El teléfono puede ser opcional
            'password'  => ['required', 'string', 'min:8', 'confirmed'],
        ]);
    }

    protected function create(array $data){
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
