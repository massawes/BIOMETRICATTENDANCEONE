<?php

namespace App\Console\Commands;

use App\Models\Attendance;
use App\Models\BiometricAttendanceSession;
use App\Models\Student;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Throwable;

class SyncZkbioAttendance extends Command
{
    protected $signature = 'zkbio:sync-attendance
        {--limit=100 : Maximum number of ZKBio logs to process}
        {--since= : Only read ZKBio logs from this punch_time onward}
        {--allow-outside-class : Save attendance even when scan time is outside timetable}
        {--retry-skipped : Retry logs that were previously skipped}
        {--retry-errors : Retry logs that previously failed with errors}
        {--dry-run : Show what would happen without writing anything}';

    protected $description = 'Sync attendance logs from ZKBio Time MySQL tables into Laravel attendances.';

    public function handle(): int
    {
        $limit = max(1, (int) $this->option('limit'));
        $activeSessions = BiometricAttendanceSession::query()
            ->where('is_active', true)
            ->orderBy('started_at')
            ->get();

        if ($activeSessions->isEmpty()) {
            $this->info('No active biometric attendance session. Start one from Lecturer Attendance page first.');

            return self::SUCCESS;
        }

        $summary = [
            'synced' => 0,
            'skipped' => 0,
            'errors' => 0,
        ];

        foreach ($activeSessions as $session) {
            $sessionStartedAt = $session->started_at
                ->copy()
                ->timezone(config('app.timezone'))
                ->toDateTimeString();

            $logs = DB::table('iclock_transaction')
                ->select([
                    'id',
                    'emp_code',
                    'punch_time',
                    'punch_state',
                    'verify_type',
                    'terminal_sn',
                    'is_attendance',
                    'company_code',
                ])
                ->where('is_attendance', 1)
                ->where('punch_time', '>=', $sessionStartedAt)
                ->whereNotExists(function ($query) {
                    $query->selectRaw('1')
                        ->from('zkbio_attendance_syncs')
                        ->whereColumn('zkbio_attendance_syncs.zkbio_transaction_id', 'iclock_transaction.id');
                })
                ->when($this->option('since'), function ($query, string $since) {
                    $query->where('punch_time', '>=', $since);
                })
                ->orderBy('id')
                ->limit($limit)
                ->get();

            if ($logs->isEmpty()) {
                $this->info("Session {$session->id}: no new ZKBio attendance logs found.");
                continue;
            }

            foreach ($logs as $log) {
                try {
                    $result = $this->syncLog($log, $session);
                    $summary[$result]++;
                } catch (Throwable $exception) {
                    $summary['errors']++;
                    $this->recordSync($log, $session, 'error', $exception->getMessage());
                    $this->error("{$log->id}: {$log->emp_code} failed - {$exception->getMessage()}");
                }
            }
        }

        $this->info("Done. Synced: {$summary['synced']}, skipped: {$summary['skipped']}, errors: {$summary['errors']}.");

        return $summary['errors'] > 0 ? self::FAILURE : self::SUCCESS;
    }

    private function syncLog(object $log, BiometricAttendanceSession $session): string
    {
        $student = $this->findStudent($log->emp_code);
        $scannedAt = Carbon::parse($log->punch_time)->setTimezone(config('app.timezone'));

        if (! $student) {
            $message = "No student found for ZKBio emp_code {$log->emp_code}.";
            $this->recordSync($log, $session, 'skipped', $message);
            $this->warn("{$log->id}: skipped - {$message}");

            return 'skipped';
        }

        if ((int) $student->program_id !== (int) $session->course_id) {
            $message = "{$student->student_name} is not in this selected course.";
            $this->recordSync($log, $session, 'skipped', $message);
            $this->warn("{$log->id}: skipped - {$message}");

            return 'skipped';
        }

        if ($this->option('dry-run')) {
            $this->line("{$log->id}: would sync {$student->student_name} to session {$session->id} at {$scannedAt->format('Y-m-d H:i')}.");

            return 'synced';
        }

        $attributes = [
            'student_id' => $student->id,
            'module_distribution_id' => $session->module_distribution_id,
            'date' => $scannedAt->toDateString(),
            'attendance_source' => 'zkbio',
        ];

        if (Schema::hasColumn('attendances', 'class_timing_id')) {
            $attributes['class_timing_id'] = $session->class_timing_id;
        }

        if (Schema::hasColumn('attendances', 'week_id')) {
            $attributes['week_id'] = $session->week_id;
        }

        $attendance = Attendance::updateOrCreate($attributes, [
            'academic_year' => (string) $scannedAt->year,
            'is_present' => true,
        ]);

        $this->recordSync($log, $session, 'synced', 'Attendance saved.', $attendance->id);
        $this->info("{$log->id}: synced {$student->student_name} to session {$session->id}.");

        return 'synced';
    }

    private function findStudent(string $empCode): ?Student
    {
        return Student::query()
            ->where(function ($query) use ($empCode) {
                if (ctype_digit($empCode)) {
                    $query->where('fingerprint_id', (int) $empCode);
                }

                if (Schema::hasColumn('students', 'admin_number')) {
                    $query->orWhere('admin_number', $empCode);
                }
            })
            ->first();
    }

    private function recordSync(object $log, BiometricAttendanceSession $session, string $status, ?string $message = null, ?int $attendanceId = null): void
    {
        if ($this->option('dry-run')) {
            return;
        }

        DB::table('zkbio_attendance_syncs')->upsert(
            [[
                'zkbio_transaction_id' => $log->id,
                'biometric_attendance_session_id' => $session->id,
                'emp_code' => $log->emp_code,
                'punch_time' => Carbon::parse($log->punch_time)->setTimezone(config('app.timezone'))->toDateTimeString(),
                'terminal_sn' => $log->terminal_sn,
                'verify_type' => $log->verify_type,
                'punch_state' => $log->punch_state,
                'status' => $status,
                'message' => $message ? mb_substr($message, 0, 255) : null,
                'attendance_id' => $attendanceId,
                'updated_at' => now(),
                'created_at' => now(),
            ]],
            ['zkbio_transaction_id'],
            [
                'biometric_attendance_session_id',
                'emp_code',
                'punch_time',
                'terminal_sn',
                'verify_type',
                'punch_state',
                'status',
                'message',
                'attendance_id',
                'updated_at',
            ]
        );
    }
}
