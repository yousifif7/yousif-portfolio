<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('skills', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('slug')->unique();
            $table->string('icon')->nullable()->comment('Font-awesome class or uploaded svg path');
            $table->string('color', 20)->nullable()->comment('Hex color for badge');
            $table->unsignedTinyInteger('proficiency')->default(80)->comment('0-100 percentage');
            $table->string('category')->nullable()->comment('e.g. Backend, Frontend, Database, DevOps');
            $table->boolean('is_active')->default(true);
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('skills');
    }
};
