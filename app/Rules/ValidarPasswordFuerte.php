<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class ValidarPasswordFuerte implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (!preg_match(config('regex.password.php'), $value)) {
            $fail(config('regex.password.error'));
        }
    }
}