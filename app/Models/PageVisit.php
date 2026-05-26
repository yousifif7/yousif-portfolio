<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PageVisit extends Model
{
    protected $fillable = [
        'url',
        'path',
        'route_name',
        'method',
        'ip_address',
        'session_id',
        'user_agent',
        'referrer',
        'country',
        'visited_at',
    ];

    protected $casts = [
        'visited_at' => 'datetime',
    ];
}
