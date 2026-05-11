<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request; 

class LoginController extends Controller{
    use AuthenticatesUsers;

    protected $redirectTo = '/';

    public function __construct(){
        $this->middleware('guest')->except('logout');
        $this->middleware('auth')->only('logout');
    }

    
    protected function validateLogin(Request $request){
        $request->validate([
            'login'    => 'required|string',
            'password' => 'required|string',
        ]);
    }

    protected function credentials(Request $request){
        $loginValue = $request->input('login');

        $field = filter_var($loginValue, FILTER_VALIDATE_EMAIL) ? 'email' : 'cedula';

        return [
            $field     => $loginValue,
            'password' => $request->input('password'),
        ];
    }

    
    public function username(){
        return 'login';
    }
}



