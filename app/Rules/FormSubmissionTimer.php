<?php

namespace App\Rules;

use App\Support\FormAntiSpam;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class FormSubmissionTimer implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (! FormAntiSpam::validateFormToken(is_string($value) ? $value : null)) {
            $fail('Please wait a few seconds after the page loads, then try again.');
        }
    }
}
