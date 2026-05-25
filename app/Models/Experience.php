<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Experience extends Model
{
    protected $fillable = [
        'position',
        'company',
        'location',
        'description',
        'start_date',
        'end_date',
        'is_current',
        'sort_order',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'is_current' => 'boolean',
    ];

    public function scopeOrdered($query)
    {
        return $query->orderByDesc('start_date');
    }

    public function getPeriodAttribute(): string
    {
        $start = $this->start_date?->format('M Y') ?? '';
        $end = $this->is_current ? 'Present' : ($this->end_date?->format('M Y') ?? '');
        return "$start - $end";
    }
}
