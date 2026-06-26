<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class ValidarTelefono implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        // El teléfono puede ser opcional (nullable en el Request), 
        // pero si llega con datos, debe cumplir la regla.
        if ($value !== null && !preg_match(config('regex.phone.php'), $value)) {
            $fail(config('regex.phone.error'));
        }
    }
}