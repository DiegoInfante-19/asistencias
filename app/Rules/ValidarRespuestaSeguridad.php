<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class ValidarRespuestaSeguridad implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (!preg_match(config('regex.respuesta_seguridad.php'), $value)) {
            $fail(config('regex.respuesta_seguridad.error'));
        }
    }
}