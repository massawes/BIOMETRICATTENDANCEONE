<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('biometric_attendance_sessions', function (Blueprint $table) {
            if (! Schema::hasColumn('biometric_attendance_sessions', 'zkbio_start_transaction_id')) {
                $table->unsignedBigInteger('zkbio_start_transaction_id')->default(0)->after('subject');
            }
        });

        if (Schema::hasTable('iclock_transaction')) {
            $latestTransactionId = (int) DB::table('iclock_transaction')->max('id');

            DB::table('biometric_attendance_sessions')
                ->where('is_active', true)
                ->update(['zkbio_start_transaction_id' => $latestTransactionId]);
        }
    }

    public function down(): void
    {
        Schema::table('biometric_attendance_sessions', function (Blueprint $table) {
            if (Schema::hasColumn('biometric_attendance_sessions', 'zkbio_start_transaction_id')) {
                $table->dropColumn('zkbio_start_transaction_id');
            }
        });
    }
};
