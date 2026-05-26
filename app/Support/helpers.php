<?php

use App\Models\Setting;
use App\Support\PublicUpload;

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
