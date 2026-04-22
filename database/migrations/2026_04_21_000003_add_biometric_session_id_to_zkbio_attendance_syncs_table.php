<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('zkbio_attendance_syncs', function (Blueprint $table) {
            if (! Schema::hasColumn('zkbio_attendance_syncs', 'biometric_attendance_session_id')) {
                $table->foreignId('biometric_attendance_session_id')
                    ->nullable()
                    ->after('id')
                    ->constrained('biometric_attendance_sessions')
                    ->nullOnDelete();
            }
        });
    }

    public function down(): void
    {
        Schema::table('zkbio_attendance_syncs', function (Blueprint $table) {
            if (Schema::hasColumn('zkbio_attendance_syncs', 'biometric_attendance_session_id')) {
                $table->dropConstrainedForeignId('biometric_attendance_session_id');
            }
        });
    }
};
