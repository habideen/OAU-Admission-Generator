<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class SessionValidation implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $years = explode('/', $value);
        
        if (!preg_match('/^[2-9][0-9]{3,3}[\/][2-9][0-9]{3,3}$/', $value)) {
            $fail("The {$attribute} format is invalid.");
        }
        elseif (
            count($years) !== 2
            || intval($years[0]) >= intval($years[1])
            || intval($years[0]) + 1 != intval($years[1])
            || intval($years[0]) >= date('Y') + 10
        ) {
            $fail("The {$attribute} is invalid.");
        }
    }
}
