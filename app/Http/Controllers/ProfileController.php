<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
// Importamos nuestros nuevos guardias
use App\Rules\ValidarUsername;
use App\Rules\ValidarNombrePropio;
use App\Rules\ValidarCedula;
use App\Rules\ValidarEmail;
use App\Rules\ValidarTelefono;

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

        // Aplicamos las nuevas reglas, conservando tus reglas previas de 'ignore'
        $request->validate([
            'username'        => ['required', 'string', 'max:20', Rule::unique('users', 'username')->ignore($user->id_users, 'id_users'), new ValidarUsername()],
            'name_users'      => ['required', 'string', 'max:50', new ValidarNombrePropio()],
            'last_name_users' => ['required', 'string', 'max:50', new ValidarNombrePropio()],
            'cedula_users'    => ['required', 'string', 'max:20', Rule::unique('users', 'cedula_users')->ignore($user->id_users, 'id_users'), new ValidarCedula()],
            'email_users'     => ['required', 'string', 'email:rfc,dns', Rule::unique('users', 'email_users')->ignore($user->id_users, 'id_users'), new ValidarEmail()],
            'phone_users'     => ['nullable', 'string', Rule::unique('users', 'phone_users')->ignore($user->id_users, 'id_users'), new ValidarTelefono()],
        ], [
            // Solo dejamos los mensajes de base de datos
            'required'             => 'Este campo es obligatorio.',
            'email_users.unique'   => 'Este correo electrónico ya está registrado.',
            'cedula_users.unique'  => 'Esta cédula ya está registrada.',
            'phone_users.unique'   => 'Este número de teléfono ya está registrado.',
            'username.unique'      => 'Este nombre de usuario ya está en uso.',
        ]);

        $user->update($request->only([
            'username', 'name_users', 'last_name_users', 'phone_users', 'cedula_users', 'email_users'
        ]));

        return back()->with('success', 'Perfil actualizado correctamente.');
    }
}