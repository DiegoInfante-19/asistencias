<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\ValidarPasswordFuerte;

class PasswordUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'current_password' => ['required', 'current_password'], 
            'password'         => ['required', 'string', 'confirmed', new ValidarPasswordFuerte()],
        ];
    }

    public function messages(): array
    {
        return [
            'current_password.current_password' => 'La contraseña actual es incorrecta.',
            'password.confirmed'                => 'Las contraseñas no coinciden.',
        ];
    }
}