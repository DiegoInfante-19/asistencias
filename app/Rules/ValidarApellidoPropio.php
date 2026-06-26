<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Translation\PotentiallyTranslatedString;

class ValidarApellidoPropio implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  Closure(string, ?string=): PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        // Apunta a la nueva llave 'last_name'
        if (!preg_match(config('regex.last_name.php'), $value)) {
            $fail(config('regex.last_name.error'));
        }
    }
}
