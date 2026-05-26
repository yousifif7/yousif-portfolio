<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    private array $defaults = [
        // Identity
        ['key' => 'site_logo', 'value' => 'brand/logo.svg', 'type' => 'image', 'group' => 'identity'],
        ['key' => 'site_favicon', 'value' => 'brand/favicon.svg', 'type' => 'image', 'group' => 'identity'],

        // Brand colors
        ['key' => 'color_primary', 'value' => '#4F46E5', 'type' => 'color', 'group' => 'colors'],
        ['key' => 'color_accent', 'value' => '#F97316', 'type' => 'color', 'group' => 'colors'],
        ['key' => 'color_dark', 'value' => '#0F172A', 'type' => 'color', 'group' => 'colors'],
        ['key' => 'color_light', 'value' => '#FAF9F6', 'type' => 'color', 'group' => 'colors'],
    ];

    public function up(): void
    {
        $now = now();

        foreach ($this->defaults as $row) {
            $existing = DB::table('settings')->where('key', $row['key'])->first();

            if ($existing && ! empty($existing->value)) {
                continue;
            }

            if ($existing) {
                DB::table('settings')->where('key', $row['key'])->update([
                    'value' => $row['value'],
                    'type' => $row['type'],
                    'group' => $row['group'],
                    'updated_at' => $now,
                ]);
            } else {
                DB::table('settings')->insert(array_merge($row, [
                    'created_at' => $now,
                    'updated_at' => $now,
                ]));
            }
        }
    }

    public function down(): void
    {
        DB::table('settings')->whereIn('key', array_column($this->defaults, 'key'))->delete();
    }
};
