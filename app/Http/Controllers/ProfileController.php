<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        return view('perfil.index', compact('user'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        // Aplicamos las mismas reglas de validación que en UserUpdateRequest
        $request->validate([
            'username'        => ['required', 'string', 'max:20', Rule::unique('users', 'username')->ignore($user->id_users, 'id_users'), 'regex:/^[A-Z](?=.*\d)[a-zA-Z0-9_]{3,19}$/'],
            'name_users'      => ['required', 'string', 'max:50', 'regex:/^[a-zA-ZñÑáéíóúÁÉÍÓÚ\s]{3,50}$/'],
            'last_name_users' => ['required', 'string', 'max:50', 'regex:/^[a-zA-ZñÑáéíóúÁÉÍÓÚ\s]{3,50}$/'],
            'cedula_users'    => ['required', 'string', 'max:20', Rule::unique('users', 'cedula_users')->ignore($user->id_users, 'id_users'), 'regex:/^\d{6,8}$/'],
            'email_users'     => ['required', 'string', 'email:rfc,dns', Rule::unique('users', 'email_users')->ignore($user->id_users, 'id_users'), 'regex:/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/'],
            'phone_users'     => ['nullable', 'string', Rule::unique('users', 'phone_users')->ignore($user->id_users, 'id_users'), 'regex:/^\d{10,11}$/'],
        ], [
            // Mensajes sincronizados con UserStoreRequest/Update
            'required'             => 'Este campo es obligatorio.',
            'email_users.unique'   => 'Este correo electrónico ya está registrado.',
            'cedula_users.unique'  => 'Esta cédula ya está registrada.',
            'phone_users.unique'   => 'Este número de teléfono ya está registrado.',
            'username.unique'      => 'Este nombre de usuario ya está en uso.',
            'username.regex'       => 'Debe iniciar con mayúscula, tener al menos un número y entre 4-20 caracteres. Sin espacios.',
            'email_users.regex'    => 'Ingrese un correo electrónico válido.',
            'name_users.regex'     => 'Solo letras y espacios (mínimo 3 caracteres).',
            'last_name_users.regex'=> 'Solo letras y espacios (mínimo 3 caracteres).',
            'cedula_users.regex'   => 'La cédula debe tener entre 6 y 8 números exactos. Sin espacios.',
            'phone_users.regex'    => 'El teléfono debe tener entre 10 y 11 números, sin guiones ni espacios.',
        ]);

        // Actualización segura
        $user->update($request->only([
            'username', 'name_users', 'last_name_users', 'phone_users', 'cedula_users', 'email_users'
        ]));

        return back()->with('success', 'Perfil actualizado correctamente.');
    }
}