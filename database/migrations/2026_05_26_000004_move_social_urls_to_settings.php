<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    private array $columns = [
        'github_url',
        'linkedin_url',
        'twitter_url',
        'facebook_url',
        'instagram_url',
        'stackoverflow_url',
    ];

    public function up(): void
    {
        $about = DB::table('abouts')->first();

        if ($about) {
            foreach ($this->columns as $col) {
                if (! empty($about->{$col})) {
                    DB::table('settings')->updateOrInsert(
                        ['key' => $col],
                        ['value' => $about->{$col}, 'type' => 'url', 'group' => 'links', 'updated_at' => now(), 'created_at' => now()]
                    );
                }
            }
        }

        Schema::table('abouts', function (Blueprint $table) {
            foreach ($this->columns as $col) {
                if (Schema::hasColumn('abouts', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }

    public function down(): void
    {
        Schema::table('abouts', function (Blueprint $table) {
            foreach ($this->columns as $col) {
                if (! Schema::hasColumn('abouts', $col)) {
                    $table->string($col)->nullable();
                }
            }
        });

        $about = DB::table('abouts')->first();
        if ($about) {
            foreach ($this->columns as $col) {
                $value = DB::table('settings')->where('key', $col)->value('value');
                if ($value) {
                    DB::table('abouts')->where('id', $about->id)->update([$col => $value]);
                }
            }
        }

        DB::table('settings')->whereIn('key', $this->columns)->delete();
    }
};
