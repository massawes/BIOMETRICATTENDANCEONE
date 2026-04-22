<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('attendances', function (Blueprint $table) {
        $table->id();
        $table->foreignId('module_distribution_id')->constrained('module_distributions')->onDelete('cascade');
        $table->foreignId('class_timing_id')->nullable()->constrained('class_timings')->nullOnDelete();
        $table->foreignId('week_id')->nullable()->constrained('weeks')->nullOnDelete();
        $table->foreignId('student_id')->constrained('students')->onDelete('cascade');
        $table->string('academic_year');
        $table->date('date');
        $table->boolean('is_present')->default(false);
        $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendances');
    }
};
