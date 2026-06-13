<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UserStoreRequest extends FormRequest
{
    /**
     * Determina si el usuario está autorizado para hacer esta petición.
     */
    public function authorize()
    {
        // Cambiamos false a true para permitir que el formulario pase
        return true; 
    }

    /**
     * Obtiene las reglas de validación que se aplicarán a la petición.
     */
    public function rules()
    {
        return [
            'name'      => ['required', 'string', 'max:50', 'regex:/^[a-zA-ZñÑáéíóúÁÉÍÓÚ\s]+$/'],
            'last_name' => ['required', 'string', 'max:50', 'regex:/^[a-zA-ZñÑáéíóúÁÉÍÓÚ\s]+$/'],
            'cedula'    => ['required', 'string', 'unique:users,cedula', 'regex:/^\d{6,8}$/'],
            'email'     => ['required', 'string', 'email:rfc,dns', 'unique:users,email', 'regex:/^[a-zA-Z0-9._%+-]{1,64}@[a-zA-Z0-9.-]{1,251}\.com$/'],
            'username'  => ['required', 'string', 'max:20', 'unique:users,username', 'regex:/^[A-Z](?=.*\d)[a-z0-9]{3,19}$/'],
            'phone'     => ['nullable', 'string', 'unique:users,phone', 'regex:/^\d{10,11}$/'],
            'role_id'   => ['required', 'exists:roles,id'],
            'password'  => [
                'required', 'string', 'confirmed',
                'regex:/^(?=(?:[^A-Z]*[A-Z]){2}[^A-Z]*$)(?=(?:[^a-z]*[a-z]){2}[^a-z]*$)(?=(?:[^0-9]*[0-9]){2}[^0-9]*$)(?=(?:[^+*$%&-]*[+*$%&-]){2}[^+*$%&-]*$)[A-Za-z0-9+*$%&-]{8}$/'
            ],
        ];
    }

    /**
     * Obtiene los mensajes de error personalizados.
     */
    public function messages()
    {
        return [
            'required'           => 'Este campo es obligatorio.',
            'email.unique'       => 'El valor del campo email ya está en uso.',
            'cedula.unique'      => 'El valor del campo cedula ya está en uso.',
            'phone.unique'       => 'Este número de teléfono ya está registrado.',
            'username.unique'    => 'El nombre de usuario ya está en uso.',
            'username.regex'     => 'Debe iniciar con mayúscula, usar minúsculas y al menos un número (4-20 caracteres).',
            'email.regex'        => 'Debe ser un correo válido terminado en .com.',
            'name.regex'         => 'Solo letras y espacios permitidos.',
            'last_name.regex'    => 'Solo letras y espacios permitidos.',
            'cedula.regex'       => 'Debe tener entre 6 y 8 números.',
            'phone.regex'        => 'Debe tener entre 10 y 11 números sin guiones.',
            'password.regex'     => 'Formato estricto: 8 caracteres (2 mayús, 2 minús, 2 núm, 2 especiales).',
            'password.confirmed' => 'Las contraseñas no coinciden.',
        ];
    }
}