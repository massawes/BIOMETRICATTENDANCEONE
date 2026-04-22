<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('attendances', function (Blueprint $table) {
            $table->index(
                ['week_id', 'attendance_source', 'student_id', 'class_timing_id', 'module_distribution_id'],
                'attendances_fingerprint_lookup_index'
            );
        });
    }

    public function down(): void
    {
        Schema::table('attendances', function (Blueprint $table) {
            $table->dropIndex('attendances_fingerprint_lookup_index');
        });
    }
};
