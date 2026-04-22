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

        return response()->json([
            'ok' => true,
            'changed' => $after > $before || $afterUpdatedAt !== $beforeUpdatedAt,
            'latest_transaction_id' => $after,
            'present_count' => $afterPresentCount,
            'new_present_count' => max(0, $afterPresentCount - $beforePresentCount),
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
}
