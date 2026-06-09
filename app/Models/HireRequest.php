<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HireRequest extends Model
{
    protected $fillable = [
        'name',
        'email',
        'whatsapp_country_code',
        'whatsapp_number',
        'offerings',
        'engagement_models',
        'project_phases',
        'message',
        'attachment_path',
        'terms_agreed',
        'is_read',
        'ip_address',
    ];

    protected $casts = [
        'offerings' => 'array',
        'engagement_models' => 'array',
        'project_phases' => 'array',
        'terms_agreed' => 'boolean',
        'is_read' => 'boolean',
    ];

    public function scopeUnread($query)
    {
        return $query->where('is_read', false);
    }

    public function getWhatsappFullAttribute(): string
    {
        return $this->whatsapp_country_code.$this->whatsapp_number;
    }

    public function getWhatsappUrlAttribute(): string
    {
        $digits = preg_replace('/[^0-9]/', '', $this->whatsapp_full);

        return 'https://wa.me/'.$digits;
    }
}
