<?php

use App\Models\Setting;
use App\Support\PublicUpload;
use App\Support\SimpleTextFormatter;

if (! function_exists('setting')) {
    function setting(string $key, $default = null)
    {
        return Setting::get($key, $default);
    }
}

if (! function_exists('setting_url')) {
    function setting_url(string $key, ?string $fallback = null): ?string
    {
        $value = Setting::get($key);
        return $value ? PublicUpload::url($value, $fallback) : $fallback;
    }
}

if (! function_exists('format_text')) {
    /**
     * Convert plain admin textarea text into safe HTML
     * (bullet/numbered lists + auto-linked URLs).
     */
    function format_text(?string $text): string
    {
        return SimpleTextFormatter::toHtml($text);
    }
}
