<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('class_timings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('module_distribution_id')->constrained('module_distributions')->onDelete('cascade');
            $table->foreignId('week_id')->nullable()->constrained('weeks')->nullOnDelete();
            $table->string('day', 20);
            $table->string('time', 50);
            $table->string('room');
            $table->timestamps();

            $table->index(['module_distribution_id', 'week_id', 'day']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('class_timings');
    }
};
