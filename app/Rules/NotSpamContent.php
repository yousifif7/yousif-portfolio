<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class NotSpamContent implements ValidationRule
{
    /** @var list<string> */
    private const PATTERNS = [
        '/\b(jackpot|bitcoin|btc|crypto wallet|casino|viagra|cialis)\b/i',
        '/\$[\d,]+(?:\.\d+)?\s*(?:million|jackpot)/i',
        '/withdraw instantly/i',
        '/quick seo idea/i',
        '/rank(?:ing)? (?:your )?website/i',
    ];

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (! is_string($value)) {
            return;
        }

        foreach (self::PATTERNS as $pattern) {
            if (preg_match($pattern, $value)) {
                $fail('Your message could not be sent. Please remove promotional or suspicious content.');

                return;
            }
        }
    }
}
