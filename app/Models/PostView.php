<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PostView extends Model
{
    protected $fillable = [
        'post_id',
        'ip_address',
        'session_id',
        'user_agent',
        'referrer',
        'viewed_on',
        'viewed_at',
    ];

    protected $casts = [
        'viewed_on' => 'date',
        'viewed_at' => 'datetime',
    ];

    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class);
    }
}
