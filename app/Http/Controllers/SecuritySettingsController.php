<?php

namespace App\Http\Controllers;

use App\Http\Requests\PasswordUpdateRequest;
use App\Http\Requests\SecurityQuestionsRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class SecuritySettingsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function updatePassword(PasswordUpdateRequest $request)
    {
        $user = Auth::user();

        // REEMPLAZA 'password_users' con el nombre exacto de tu columna en la base de datos.
        $user->update([
            'password_users' => Hash::make($request->password)
        ]);

        return back()->with('success', 'Contraseña actualizada exitosamente.');
    }

    public function updateSecurityQuestions(SecurityQuestionsRequest $request)
    {
        // El FormRequest ya validó: preguntas existentes e integridad de respuestas.
        Auth::user()->update([
            'pregunta1'  => $request->pregunta1,
            'respuesta1' => Hash::make($request->respuesta1), // Hashing para seguridad
            'pregunta2'  => $request->pregunta2,
            'respuesta2' => Hash::make($request->respuesta2), // Hashing para seguridad
        ]);

        return back()->with('success', 'Preguntas de seguridad configuradas correctamente.');
    }
}
