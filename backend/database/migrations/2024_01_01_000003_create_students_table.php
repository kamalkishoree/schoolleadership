<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_id')->constrained()->onDelete('cascade');
            $table->foreignId('class_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->string('email_or_mobile')->unique();
            $table->string('password');
            $table->unsignedInteger('total_xp')->default(0);
            $table->unsignedInteger('current_streak')->default(0);
            $table->date('last_active_date')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['school_id', 'class_id']);
            $table->index('email_or_mobile');
            $table->index('total_xp');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};

