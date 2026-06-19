<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PasswordUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        // IMPORTANTE: Cambiado a true para permitir el envío
        return true;
    }

    public function rules(): array
    {
        return [
            // Validamos que la clave actual sea real
            'current_password' => ['required', 'current_password'], 
            
            // Regla exacta que ya tienes en el sistema
            'password' => [
                'required', 
                'string', 
                'confirmed', 
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&.+-])[A-Za-z\d@$!%*?&.+-]{8,64}$/'
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'current_password.current_password' => 'La contraseña actual es incorrecta.',
            'password.regex' => 'Mínimo 8 caracteres: al menos una mayúscula, una minúscula, un número y un símbolo.',
            'password.confirmed' => 'Las contraseñas no coinciden.',
        ];
    }
}