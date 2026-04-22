<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Device;
use App\Models\Student;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class Esp32Controller extends Controller
{
    public function deviceMode(string $device_uid): JsonResponse
    {
        $device = $this->findDevice($device_uid);

        return response()->json([
            'status' => 'success',
            'device_uid' => $device->device_uid,
            'mode' => (int) $device->device_mode,
            'mode_name' => (int) $device->device_mode === 0 ? 'enrollment' : 'attendance',
        ]);
    }

    public function enrollmentRequest(string $device_uid): JsonResponse
    {
        $this->findDevice($device_uid);

        $request = Cache::get($this->enrollmentCacheKey($device_uid));

        if (! $request) {
            return response()->json([
                'status' => 'idle',
                'message' => 'No enrollment request pending.',
            ]);
        }

        return response()->json([
            'status' => 'pending',
            'student_id' => $request['student_id'],
            'fingerprint_id' => $request['fingerprint_id'],
            'student_name' => $request['student_name'],
        ]);
    }

    public function storeEnrollmentRequest(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'device_uid' => 'required|string|exists:devices,device_uid',
            'student_id' => 'required|exists:students,id',
            'fingerprint_id' => 'required|integer|min:1|max:127|unique:students,fingerprint_id',
        ]);

        $reservations = Cache::get($this->fingerprintReservationCacheKey(), []);

        if (isset($reservations[$validated['fingerprint_id']])) {
            return response()->json([
                'status' => 'error',
                'message' => 'Fingerprint ID is already reserved.',
            ], 409);
        }

        $student = Student::findOrFail($validated['student_id']);

        Cache::put($this->enrollmentCacheKey($validated['device_uid']), [
            'student_id' => $student->id,
            'student_name' => $student->student_name,
            'fingerprint_id' => $validated['fingerprint_id'],
        ], now()->addMinutes(30));

        return response()->json([
            'status' => 'success',
            'message' => 'Enrollment request queued.',
        ]);
    }

    public function confirmEnrollment(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'device_uid' => 'required|string|exists:devices,device_uid',
            'fingerprint_id' => 'required|integer|min:1|max:127',
        ]);

        $pending = Cache::get($this->enrollmentCacheKey($validated['device_uid']));

        if (! $pending || (int) $pending['fingerprint_id'] !== (int) $validated['fingerprint_id']) {
            return response()->json([
                'status' => 'error',
                'message' => 'No matching enrollment request found.',
            ], 404);
        }

        Student::whereKey($pending['student_id'])->update([
            'fingerprint_id' => $validated['fingerprint_id'],
        ]);

        $this->releaseFingerprintReservation((int) $validated['fingerprint_id']);
        Cache::forget($this->enrollmentCacheKey($validated['device_uid']));

        return response()->json([
            'status' => 'success',
            'message' => 'Fingerprint linked successfully.',
            'student_name' => $pending['student_name'],
        ]);
    }

    public function deletionRequest(string $device_uid): JsonResponse
    {
        $this->findDevice($device_uid);

        $request = Cache::get($this->deletionCacheKey($device_uid));

        if (! $request) {
            return response()->json([
                'status' => 'idle',
                'message' => 'No deletion request pending.',
            ]);
        }

        return response()->json([
            'status' => 'pending',
            'fingerprint_id' => $request['fingerprint_id'],
        ]);
    }

    public function storeDeletionRequest(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'device_uid' => 'required|string|exists:devices,device_uid',
            'fingerprint_id' => 'required|integer|min:1|max:127|exists:students,fingerprint_id',
        ]);

        Cache::put($this->deletionCacheKey($validated['device_uid']), [
            'fingerprint_id' => $validated['fingerprint_id'],
        ], now()->addMinutes(30));

        return response()->json([
            'status' => 'success',
            'message' => 'Deletion request queued.',
        ]);
    }

    public function confirmDeletion(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'device_uid' => 'required|string|exists:devices,device_uid',
            'fingerprint_id' => 'required|integer|min:1|max:127',
        ]);

        $pending = Cache::get($this->deletionCacheKey($validated['device_uid']));

        if (! $pending || (int) $pending['fingerprint_id'] !== (int) $validated['fingerprint_id']) {
            return response()->json([
                'status' => 'error',
                'message' => 'No matching deletion request found.',
            ], 404);
        }

        Student::where('fingerprint_id', $validated['fingerprint_id'])->update([
            'fingerprint_id' => null,
        ]);

        Cache::forget($this->deletionCacheKey($validated['device_uid']));

        return response()->json([
            'status' => 'success',
            'message' => 'Fingerprint removed successfully.',
        ]);
    }

    public function storeAttendance(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'device_uid' => 'required|string|exists:devices,device_uid',
            'fingerprint_id' => 'required|integer|min:1|max:127',
            'scanned_at' => 'nullable|date',
        ]);

        $device = $this->findDevice($validated['device_uid']);
        $hasAttendanceSource = Schema::hasColumn('attendances', 'attendance_source');

        if ((int) $device->device_mode !== 1) {
            return response()->json([
                'status' => 'error',
                'message' => 'Device is not in attendance mode.',
            ], 409);
        }

        $student = Student::with('program')
            ->where('fingerprint_id', $validated['fingerprint_id'])
            ->first();

        if (! $student) {
            return response()->json([
                'status' => 'error',
                'message' => 'Fingerprint not linked to any student.',
            ], 404);
        }

        $scannedAt = isset($validated['scanned_at'])
            ? Carbon::parse($validated['scanned_at'], config('app.timezone'))
            : now();

        $activeClass = $this->resolveActiveClass($student, $scannedAt);

        if (! $activeClass) {
            return response()->json([
                'status' => 'error',
                'message' => 'No active class found for this fingerprint scan.',
                'student_name' => $student->student_name,
            ], 404);
        }

        $attributes = [
            'student_id' => $student->id,
            'module_distribution_id' => $activeClass->module_distribution_id,
            'date' => $scannedAt->toDateString(),
        ];

        if ($hasAttendanceSource) {
            $attributes['attendance_source'] = 'fingerprint';
        }

        if (Schema::hasColumn('attendances', 'class_timing_id')) {
            $attributes['class_timing_id'] = $activeClass->class_timing_id;
        }

        if (Schema::hasColumn('attendances', 'week_id')) {
            $currentWeekId = $this->resolveCurrentWeekId();

            if ($currentWeekId) {
                $attributes['week_id'] = $currentWeekId;
            }
        }

        $values = [
            'academic_year' => (string) $scannedAt->year,
            'is_present' => true,
        ];

        Attendance::updateOrCreate($attributes, $values);

        return response()->json([
            'status' => 'success',
            'message' => 'Attendance saved successfully.',
            'student_name' => $student->student_name,
            'fingerprint_id' => $validated['fingerprint_id'],
            'class_timing_id' => $activeClass->class_timing_id,
            'module_distribution_id' => $activeClass->module_distribution_id,
        ]);
    }

    private function findDevice(string $device_uid): Device
    {
        return Device::where('device_uid', $device_uid)->firstOrFail();
    }

    private function enrollmentCacheKey(string $device_uid): string
    {
        return 'esp32:enrollment:' . $device_uid;
    }

    private function deletionCacheKey(string $device_uid): string
    {
        return 'esp32:deletion:' . $device_uid;
    }

    private function fingerprintReservationCacheKey(): string
    {
        return 'esp32:fingerprint:reservations';
    }

    private function releaseFingerprintReservation(int $fingerprintId): void
    {
        $reservations = Cache::get($this->fingerprintReservationCacheKey(), []);

        if (isset($reservations[$fingerprintId])) {
            unset($reservations[$fingerprintId]);
            Cache::put($this->fingerprintReservationCacheKey(), $reservations, now()->addMinutes(30));
        }
    }

    private function resolveCurrentWeekId(): ?int
    {
        $weekId = DB::table('weeks')
            ->where('allowed', 1)
            ->orderBy('id')
            ->value('id');

        return $weekId ? (int) $weekId : null;
    }

    private function resolveActiveClass(Student $student, Carbon $scannedAt): ?object
    {
        $dayName = $scannedAt->englishDayOfWeek;
        $currentTime = $scannedAt->format('H:i');

        $classes = DB::table('class_timings as ct')
            ->join('module_distributions as md', 'ct.module_distribution_id', '=', 'md.id')
            ->join('modules as m', 'md.module_id', '=', 'm.id')
            ->where('m.program_id', $student->program_id)
            ->where('ct.day', $dayName)
            ->select(
                'ct.id as class_timing_id',
                'ct.time',
                'md.id as module_distribution_id'
            )
            ->get();

        foreach ($classes as $class) {
            if ($this->timeMatches($class->time, $currentTime)) {
                return $class;
            }
        }

        return null;
    }

    private function timeMatches(?string $range, string $currentTime): bool
    {
        if (! $range || ! str_contains($range, '-')) {
            return false;
        }

        [$start, $end] = preg_split('/\s*-\s*/', trim($range), 2);

        if (! $start || ! $end) {
            return false;
        }

        return $currentTime >= trim($start) && $currentTime <= trim($end);
    }
}
