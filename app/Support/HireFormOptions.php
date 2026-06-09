<?php

namespace App\Support;

class HireFormOptions
{
    public static function engagementModels(): array
    {
        return config('hire.engagement_models') ?? [
            'hourly' => 'Hourly Rate',
            'dedicated' => 'Dedicated Team / Individual',
            'fixed' => 'Fixed Price',
        ];
    }

    public static function projectPhases(): array
    {
        return config('hire.project_phases') ?? [
            'research' => 'Market Research',
            'design' => 'Design & Analysis',
            'development' => 'Starting Development',
            'maintenance' => 'Maintenance & Support',
        ];
    }

    public static function countryCodes(): array
    {
        return config('country_codes') ?? [
            ['code' => '+970', 'label' => 'Palestine (+970)', 'flag' => '🇵🇸'],
            ['code' => '+972', 'label' => 'Israel (+972)', 'flag' => '🇮🇱'],
            ['code' => '+1', 'label' => 'USA / Canada (+1)', 'flag' => '🇺🇸'],
            ['code' => '+44', 'label' => 'United Kingdom (+44)', 'flag' => '🇬🇧'],
            ['code' => '+971', 'label' => 'UAE (+971)', 'flag' => '🇦🇪'],
            ['code' => '+966', 'label' => 'Saudi Arabia (+966)', 'flag' => '🇸🇦'],
            ['code' => '+20', 'label' => 'Egypt (+20)', 'flag' => '🇪🇬'],
            ['code' => '+962', 'label' => 'Jordan (+962)', 'flag' => '🇯🇴'],
        ];
    }
}
