<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class ValidarCedula implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (!preg_match(config('regex.cedula.php'), $value)) {
            $fail(config('regex.cedula.error'));
        }
    }
}