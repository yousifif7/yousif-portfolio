<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('abouts', function (Blueprint $table) {
            $table->id();
            $table->string('full_name');
            $table->string('title')->comment('e.g. Backend Laravel Developer');
            $table->string('tagline')->nullable()->comment('Short hero tagline');
            $table->text('short_bio')->comment('Hero / about-me intro');
            $table->longText('long_bio')->nullable()->comment('Detailed bio for about page');
            $table->integer('years_of_experience')->default(0);
            $table->string('location')->nullable();
            $table->string('nationality')->nullable();
            $table->string('education_degree')->nullable();
            $table->string('education_university')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('whatsapp')->nullable();
            $table->string('avatar')->nullable();
            $table->string('cv_file')->nullable();
            $table->string('github_url')->nullable();
            $table->string('linkedin_url')->nullable();
            $table->string('twitter_url')->nullable();
            $table->string('facebook_url')->nullable();
            $table->string('instagram_url')->nullable();
            $table->string('stackoverflow_url')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('abouts');
    }
};
