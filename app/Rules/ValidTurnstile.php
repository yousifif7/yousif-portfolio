<?php

namespace App\Rules;

use App\Support\FormAntiSpam;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class ValidTurnstile implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $response = is_string($value) ? $value : null;

        if (! FormAntiSpam::verifyTurnstile($response, request()->ip())) {
            $fail('Security verification failed. Please try again.');
        }
    }
}
