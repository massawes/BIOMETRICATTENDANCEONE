<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('biometric_attendance_sessions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lecturer_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('week_id')->constrained('weeks')->cascadeOnDelete();
            $table->foreignId('course_id')->constrained('programs')->cascadeOnDelete();
            $table->foreignId('module_distribution_id')->constrained('module_distributions')->cascadeOnDelete();
            $table->foreignId('class_timing_id')->constrained('class_timings')->cascadeOnDelete();
            $table->string('day', 20);
            $table->string('subject');
            $table->timestamp('started_at');
            $table->timestamp('ended_at')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index(['lecturer_id', 'is_active']);
            $table->index(['week_id', 'course_id', 'module_distribution_id', 'class_timing_id'], 'biometric_session_context_index');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('biometric_attendance_sessions');
    }
};
