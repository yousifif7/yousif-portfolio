<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('page_visits', function (Blueprint $table) {
            $table->id();
            $table->string('url', 2048);
            $table->string('path', 1024)->index();
            $table->string('route_name')->nullable()->index();
            $table->string('method', 10)->default('GET');
            $table->string('ip_address', 45)->nullable()->index();
            $table->string('session_id', 64)->nullable()->index();
            $table->text('user_agent')->nullable();
            $table->string('referrer', 2048)->nullable();
            $table->string('country', 2)->nullable();
            $table->timestamp('visited_at')->index();
            $table->timestamps();

            $table->index(['visited_at', 'ip_address']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('page_visits');
    }
};
