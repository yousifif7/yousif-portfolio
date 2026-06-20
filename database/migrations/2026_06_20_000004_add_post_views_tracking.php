<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->unsignedInteger('views')->default(0)->after('reading_time_minutes');
            $table->unsignedInteger('unique_views')->default(0)->after('views');
        });

        Schema::create('post_views', function (Blueprint $table) {
            $table->id();
            $table->foreignId('post_id')->constrained()->cascadeOnDelete();
            $table->string('ip_address', 45)->nullable();
            $table->string('session_id', 64)->nullable();
            $table->text('user_agent')->nullable();
            $table->string('referrer', 2048)->nullable();
            $table->date('viewed_on')->index();
            $table->timestamp('viewed_at');
            $table->timestamps();

            $table->index(['post_id', 'viewed_on']);
            $table->unique(['post_id', 'ip_address', 'viewed_on'], 'post_views_unique_per_day');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('post_views');

        Schema::table('posts', function (Blueprint $table) {
            $table->dropColumn(['views', 'unique_views']);
        });
    }
};
