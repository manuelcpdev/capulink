<?php

namespace App\Rules;

use Closure;
use Dotenv\Util\Regex;
use Illuminate\Contracts\Validation\ValidationRule;

class LigazonValida implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $patron = '/^[A-Za-z][A-Za-z\d.+-]*:\/*(?:\w+(?::\w+)?@)?[^\s/]+(?::\d+)?(?:\/[\w#!:.?+=&%@\-/]*)?$/';
        if (!preg_match($patron, $value)) {
            $fail('A ligazón non está ben formada.');
        }
    }
}
