<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Setting extends Model
{
    protected $fillable = ['key', 'value', 'type'];

    public static function get(string $key, $default = null)
    {
        return Cache::rememberForever("setting.$key", function () use ($key, $default) {
            $setting = static::where('key', $key)->first();
            return $setting?->value ?? $default;
        });
    }

    public static function set(string $key, $value, string $type = 'text'): self
    {
        $setting = static::updateOrCreate(['key' => $key], ['value' => $value, 'type' => $type]);
        Cache::forget("setting.$key");
        return $setting;
    }

    protected static function booted(): void
    {
        static::saved(fn (Setting $s) => Cache::forget("setting.$s->key"));
        static::deleted(fn (Setting $s) => Cache::forget("setting.$s->key"));
    }
}
