<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Setting extends Model
{
    protected $fillable = ['key', 'value', 'type', 'group'];

    public static function get(string $key, $default = null)
    {
        return Cache::rememberForever("setting.$key", function () use ($key, $default) {
            $setting = static::where('key', $key)->first();
            return $setting?->value ?? $default;
        });
    }

    public static function set(string $key, $value, string $type = 'text', string $group = 'general'): self
    {
        $setting = static::updateOrCreate(
            ['key' => $key],
            ['value' => $value, 'type' => $type, 'group' => $group]
        );
        Cache::forget("setting.$key");
        Cache::forget('settings.all');
        return $setting;
    }

    public static function all_keyed(): array
    {
        return Cache::rememberForever('settings.all', function () {
            return static::query()->pluck('value', 'key')->toArray();
        });
    }

    protected static function booted(): void
    {
        static::saved(function (Setting $s) {
            Cache::forget("setting.$s->key");
            Cache::forget('settings.all');
        });
        static::deleted(function (Setting $s) {
            Cache::forget("setting.$s->key");
            Cache::forget('settings.all');
        });
    }
}
