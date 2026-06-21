<?php

namespace App\Support;

use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Http;

class FormAntiSpam
{
    public const MIN_SECONDS = 3;

    public const MAX_SECONDS = 3600;

    public static function formToken(): string
    {
        return Crypt::encryptString((string) now()->timestamp);
    }

    public static function validateFormToken(?string $token): bool
    {
        if (! filled($token)) {
            return false;
        }

        try {
            $startedAt = (int) Crypt::decryptString($token);
        } catch (\Throwable) {
            return false;
        }

        $elapsed = now()->timestamp - $startedAt;

        return $elapsed >= self::MIN_SECONDS && $elapsed <= self::MAX_SECONDS;
    }

    public static function turnstileEnabled(): bool
    {
        return filled(config('services.turnstile.site_key'))
            && filled(config('services.turnstile.secret_key'));
    }

    public static function verifyTurnstile(?string $response, ?string $ip = null): bool
    {
        if (! self::turnstileEnabled()) {
            return true;
        }

        if (! filled($response)) {
            return false;
        }

        $result = Http::timeout(5)->asForm()->post(
            'https://challenges.cloudflare.com/turnstile/v0/siteverify',
            array_filter([
                'secret' => config('services.turnstile.secret_key'),
                'response' => $response,
                'remoteip' => $ip,
            ])
        );

        if (! $result->successful()) {
            return false;
        }

        return (bool) $result->json('success');
    }
}
