<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

class ZkbioRealtimeController extends Controller
{
    public function sync(Request $request): JsonResponse
    {
        $sessionId = $request->integer('session_id');
        $beforePresentCount = $sessionId ? $this->presentCountForSession($sessionId) : 0;
        $before = (int) DB::table('zkbio_attendance_syncs')->max('zkbio_transaction_id');
        $beforeUpdatedAt = DB::table('zkbio_attendance_syncs')->max('updated_at');

        Artisan::call('zkbio:sync-attendance', [
            '--limit' => 100,
        ]);

        $after = (int) DB::table('zkbio_attendance_syncs')->max('zkbio_transaction_id');
        $afterUpdatedAt = DB::table('zkbio_attendance_syncs')->max('updated_at');
        $afterPresentCount = $sessionId ? $this->presentCountForSession($sessionId) : 0;
        $markedStudents = $sessionId
            ? $this->markedStudentsForSession($sessionId, $before, $beforeUpdatedAt)
            : collect();

        return response()->json([
            'ok' => true,
            'changed' => $after > $before || $afterUpdatedAt !== $beforeUpdatedAt,
            'latest_transaction_id' => $after,
            'present_count' => $afterPresentCount,
            'new_present_count' => max(0, $afterPresentCount - $beforePresentCount),
            'marked_students' => $markedStudents,
        ]);
    }

    private function presentCountForSession(int $sessionId): int
    {
        $session = DB::table('biometric_attendance_sessions')
            ->where('id', $sessionId)
            ->where('lecturer_id', auth()->id())
            ->first();

        if (! $session) {
            return 0;
        }

        return Attendance::query()
            ->where('attendance_source', 'zkbio')
            ->where('week_id', $session->week_id)
            ->where('class_timing_id', $session->class_timing_id)
            ->where('module_distribution_id', $session->module_distribution_id)
            ->where('is_present', true)
            ->count();
    }

    private function markedStudentsForSession(int $sessionId, int $previousTransactionId, ?string $previousUpdatedAt)
    {
        $session = DB::table('biometric_attendance_sessions')
            ->where('id', $sessionId)
            ->where('lecturer_id', auth()->id())
            ->first();

        if (! $session) {
            return collect();
        }

        return DB::table('zkbio_attendance_syncs as zs')
            ->join('attendances as a', 'zs.attendance_id', '=', 'a.id')
            ->join('students as s', 'a.student_id', '=', 's.id')
            ->where('zs.biometric_attendance_session_id', $sessionId)
            ->where('zs.status', 'synced')
            ->where('a.week_id', $session->week_id)
            ->where('a.class_timing_id', $session->class_timing_id)
            ->where('a.module_distribution_id', $session->module_distribution_id)
            ->where('a.is_present', true)
            ->where(function ($query) use ($previousTransactionId, $previousUpdatedAt) {
                $query->where('zs.zkbio_transaction_id', '>', $previousTransactionId);

                if ($previousUpdatedAt) {
                    $query->orWhere('zs.updated_at', '>', $previousUpdatedAt);
                }
            })
            ->select('s.id as student_id', 's.admin_number')
            ->distinct()
            ->get();
    }
}
