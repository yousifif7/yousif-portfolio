<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('project_views', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained()->cascadeOnDelete();
            $table->string('ip_address', 45)->nullable();
            $table->string('session_id', 64)->nullable();
            $table->text('user_agent')->nullable();
            $table->string('referrer', 2048)->nullable();
            $table->date('viewed_on')->index();
            $table->timestamp('viewed_at');
            $table->timestamps();

            $table->index(['project_id', 'viewed_on']);
            $table->unique(['project_id', 'ip_address', 'viewed_on'], 'project_views_unique_per_day');
        });

        Schema::table('projects', function (Blueprint $table) {
            $table->unsignedInteger('unique_views')->default(0)->after('views');
        });
    }

    public function down(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->dropColumn('unique_views');
        });

        Schema::dropIfExists('project_views');
    }
};
