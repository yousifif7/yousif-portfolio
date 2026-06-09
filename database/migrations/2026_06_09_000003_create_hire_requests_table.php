<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('hire_requests', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email');
            $table->string('whatsapp_country_code', 8);
            $table->string('whatsapp_number', 30);
            $table->json('offerings')->nullable();
            $table->json('engagement_models')->nullable();
            $table->json('project_phases')->nullable();
            $table->text('message')->nullable();
            $table->string('attachment_path')->nullable();
            $table->boolean('terms_agreed')->default(false);
            $table->boolean('is_read')->default(false);
            $table->string('ip_address', 45)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hire_requests');
    }
};
