<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class About extends Model
{
    protected static function booted(): void
    {
        static::saved(fn () => \Illuminate\Support\Facades\Cache::forget('about.current'));
        static::deleted(fn () => \Illuminate\Support\Facades\Cache::forget('about.current'));
    }

    protected $fillable = [
        'full_name',
        'title',
        'tagline',
        'short_bio',
        'long_bio',
        'years_of_experience',
        'location',
        'nationality',
        'education_degree',
        'education_university',
        'email',
        'phone',
        'whatsapp',
        'avatar',
        'cv_file',
    ];

    public function getAvatarUrlAttribute(): string
    {
        if ($this->avatar) {
            return \App\Support\ImageOptimizer::url($this->avatar, 720, asset('images/default-avatar.svg'))
                ?? \App\Support\PublicUpload::url($this->avatar, asset('images/default-avatar.svg'));
        }

        return asset('images/default-avatar.svg');
    }

    public function getAvatarSrcsetAttribute(): ?string
    {
        return $this->avatar
            ? \App\Support\ImageOptimizer::srcset($this->avatar, [280, 360, 720])
            : null;
    }

    public function getCvUrlAttribute(): ?string
    {
        return \App\Support\PublicUpload::url($this->cv_file);
    }

    public static function current(): ?self
    {
        return static::first();
    }
}
