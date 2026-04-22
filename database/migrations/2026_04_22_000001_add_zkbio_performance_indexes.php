<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('iclock_transaction')) {
            $this->addIndexIfMissing('iclock_transaction', 'iclock_transaction_attendance_time_id_index', function (Blueprint $table) {
                $table->index(['is_attendance', 'punch_time', 'id'], 'iclock_transaction_attendance_time_id_index');
            });

            $this->addIndexIfMissing('iclock_transaction', 'iclock_transaction_emp_code_time_index', function (Blueprint $table) {
                $table->index(['emp_code', 'punch_time'], 'iclock_transaction_emp_code_time_index');
            });
        }

        if (Schema::hasTable('attendances')) {
            $this->addIndexIfMissing('attendances', 'attendances_records_page_index', function (Blueprint $table) {
                $table->index(['attendance_source', 'date', 'week_id'], 'attendances_records_page_index');
            });
        }

        if (Schema::hasTable('biometric_attendance_sessions')) {
            $this->addIndexIfMissing('biometric_attendance_sessions', 'biometric_sessions_active_lookup_index', function (Blueprint $table) {
                $table->index(['is_active', 'lecturer_id', 'week_id', 'course_id'], 'biometric_sessions_active_lookup_index');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('biometric_attendance_sessions')) {
            $this->dropIndexIfExists('biometric_attendance_sessions', 'biometric_sessions_active_lookup_index');
        }

        if (Schema::hasTable('attendances')) {
            $this->dropIndexIfExists('attendances', 'attendances_records_page_index');
        }

        if (Schema::hasTable('iclock_transaction')) {
            $this->dropIndexIfExists('iclock_transaction', 'iclock_transaction_emp_code_time_index');
            $this->dropIndexIfExists('iclock_transaction', 'iclock_transaction_attendance_time_id_index');
        }
    }

    private function addIndexIfMissing(string $table, string $index, callable $callback): void
    {
        if ($this->indexExists($table, $index)) {
            return;
        }

        Schema::table($table, $callback);
    }

    private function dropIndexIfExists(string $table, string $index): void
    {
        if (! $this->indexExists($table, $index)) {
            return;
        }

        Schema::table($table, function (Blueprint $table) use ($index) {
            $table->dropIndex($index);
        });
    }

    private function indexExists(string $table, string $index): bool
    {
        return DB::table('information_schema.statistics')
            ->where('table_schema', DB::getDatabaseName())
            ->where('table_name', $table)
            ->where('index_name', $index)
            ->exists();
    }
};
