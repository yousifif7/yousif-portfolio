<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class About extends Model
{
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
        'github_url',
        'linkedin_url',
        'twitter_url',
        'facebook_url',
        'instagram_url',
        'stackoverflow_url',
    ];

    public function getAvatarUrlAttribute(): string
    {
        if ($this->avatar && file_exists(public_path('storage/'.$this->avatar))) {
            return asset('storage/'.$this->avatar);
        }

        return asset('images/default-avatar.png');
    }

    public function getCvUrlAttribute(): ?string
    {
        if ($this->cv_file) {
            return asset('storage/'.$this->cv_file);
        }
        return null;
    }

    public static function current(): ?self
    {
        return static::first();
    }
}
