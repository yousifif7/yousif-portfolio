<?php

namespace App\Http\Requests\Concerns;

use App\Rules\FormSubmissionTimer;
use App\Rules\ValidTurnstile;
use App\Support\FormAntiSpam;

trait ValidatesAntiSpam
{
    /** @return array<string, mixed> */
    protected function antiSpamRules(): array
    {
        $rules = [
            'website' => ['nullable', 'size:0'],
            'form_token' => ['required', new FormSubmissionTimer],
        ];

        if (FormAntiSpam::turnstileEnabled()) {
            $rules['cf-turnstile-response'] = ['required', new ValidTurnstile];
        }

        return $rules;
    }

    /** @return array<string, string> */
    protected function antiSpamMessages(): array
    {
        return [
            'website.size' => 'Spam detected.',
            'form_token.required' => 'Please reload the page and try again.',
            'cf-turnstile-response.required' => 'Please complete the security check.',
        ];
    }
}
