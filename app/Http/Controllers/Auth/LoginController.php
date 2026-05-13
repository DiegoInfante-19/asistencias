<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    protected $redirectTo = '/';

    public function __construct(){
        $this->middleware('guest')->except('logout');
        $this->middleware('auth')->only('logout');
    }

    protected function validateLogin(Request $request){
        $mensajes = [
            'login.required'    => 'El campo de identificación no puede estar vacío.',
            'login.string'      => 'El formato de la identificación no es válido.',
            'password.required' => 'Debes ingresar tu contraseña para continuar.',
        ];

        $request->validate([
            'login'    => 'required|string',
            'password' => 'required|string',
        ], $mensajes);
    }

    protected function credentials(Request $request){
        $loginValue = $request->input('login');

        $field = filter_var($loginValue, FILTER_VALIDATE_EMAIL) ? 'email' : 'cedula';

        return [
            $field     => $loginValue,
            'password' => $request->input('password'),
        ];
    }

    protected function loggedOut(Request $request){
        return redirect('/login');
    }

    public function username(){
        return 'login';
    }
}
