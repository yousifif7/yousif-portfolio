<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    protected $fillable = [
        'name',
        'email',
        'rating',
        'content',
        'company',
        'role',
        'status',
        'is_featured',
        'ip_address',
        'approved_at',
    ];

    protected $casts = [
        'rating' => 'integer',
        'is_featured' => 'boolean',
        'approved_at' => 'datetime',
    ];

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderByDesc('approved_at')->orderByDesc('id');
    }

    public function getInitialsAttribute(): string
    {
        $parts = preg_split('/\s+/', trim($this->name));

        return strtoupper(
            count($parts) >= 2
                ? mb_substr($parts[0], 0, 1).mb_substr(end($parts), 0, 1)
                : mb_substr($this->name, 0, 2)
        );
    }
}
