<?php

return [
    'admin_email' => env('ADMIN_EMAIL', 'admin@example.com'),

    'turnstile' => [
        'site_key' => env('TURNSTILE_SITE_KEY'),
        'secret_key' => env('TURNSTILE_SECRET_KEY'),
    ],
];
