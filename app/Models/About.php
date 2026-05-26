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
    ];

    public function getAvatarUrlAttribute(): string
    {
        if ($this->avatar) {
            $relative = str_starts_with($this->avatar, 'uploads/') ? $this->avatar : 'storage/'.$this->avatar;
            if (file_exists(public_path($relative))) {
                return asset($relative);
            }
        }

        return asset('images/default-avatar.png');
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
