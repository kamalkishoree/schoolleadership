<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('submissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained()->onDelete('cascade');
            $table->foreignId('challenge_id')->constrained()->onDelete('cascade');
            $table->enum('selected_option', ['a', 'b', 'c', 'd']);
            $table->unsignedInteger('score');
            $table->timestamp('submitted_at');
            $table->timestamps();

            $table->unique(['student_id', 'challenge_id']);
            $table->index(['student_id', 'submitted_at']);
            $table->index('challenge_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('submissions');
    }
};

