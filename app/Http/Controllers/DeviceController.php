<?php

namespace App\Http\Controllers;

use App\Models\Device;
use App\Models\Student;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Illuminate\Http\Request;

class DeviceController extends Controller
{
    // SHOW
    public function index()
    {
        $devices = Device::latest()->get();
        $studentIds = DB::table('module_distributions as md')
            ->join('modules as m', 'md.module_id', '=', 'm.id')
            ->join('students as s', 's.program_id', '=', 'm.program_id')
            ->where('md.user_id', auth()->id())
            ->whereNull('s.fingerprint_id')
            ->distinct()
            ->pluck('s.id');

        $students = Student::with('program')
            ->whereIn('id', $studentIds)
            ->orderBy('student_name')
            ->get();

        $usedFingerprintIds = Student::query()
            ->whereNotNull('fingerprint_id')
            ->pluck('fingerprint_id');

        $reservedFingerprintIds = collect(Cache::get($this->fingerprintReservationCacheKey(), []))
            ->keys()
            ->map(fn ($fingerprintId) => (int) $fingerprintId);

        $availableFingerprintIds = collect(range(1, 127))
            ->diff($usedFingerprintIds)
            ->diff($reservedFingerprintIds)
            ->values();

        return view('lecturer.devices', compact('devices', 'students', 'availableFingerprintIds'));
    }

    // ADD DEVICE
    public function store(Request $request)
    {
        $request->validate([
            'device_name' => 'required',
            'device_dep' => 'required',
        ]);

        // AUTO GENERATE UID (KAMA PHP random_bytes)
        $device_uid = bin2hex(random_bytes(4));

        Device::create([
            'device_name' => $request->device_name,
            'device_dep' => $request->device_dep,
            'device_uid' => $device_uid,
            'device_date' => now()->toDateString(),
            'device_mode' => 1
        ]);

        return back()->with('success', 'Device Added');
    }

    // DELETE
    public function destroy($id)
    {
        Device::findOrFail($id)->delete();
        return back()->with('success', 'Device Deleted');
    }

    // UPDATE UID (refresh button)
    public function updateUID($id)
    {
        $device = Device::findOrFail($id);

        $device->update([
            'device_uid' => bin2hex(random_bytes(8))
        ]);

        return back()->with('success', 'UID Updated');
    }

    // CHANGE MODE
    public function changeMode(Request $request, $id)
    {
        $device = Device::findOrFail($id);

        $device->update([
            'device_mode' => $request->mode
        ]);

        return back()->with('success', 'Mode Updated');
    }

    public function startEnrollment(Request $request)
    {
        $validated = $request->validate([
            'device_id' => 'required|exists:devices,id',
            'student_id' => 'required|exists:students,id',
            'fingerprint_id' => 'required|integer|min:1|max:127|unique:students,fingerprint_id',
        ]);

        $device = Device::findOrFail($validated['device_id']);

        if ((int) $device->device_mode !== 0) {
            return back()->with('error', 'Set the device to Enrollment mode first.');
        }

        $reservations = Cache::get($this->fingerprintReservationCacheKey(), []);

        if (isset($reservations[$validated['fingerprint_id']])) {
            return back()->with('error', 'That fingerprint ID is already reserved. Choose another one.');
        }

        $student = Student::with('program')
            ->whereNull('fingerprint_id')
            ->whereIn('id', DB::table('module_distributions as md')
                ->join('modules as m', 'md.module_id', '=', 'm.id')
                ->join('students as s', 's.program_id', '=', 'm.program_id')
                ->where('md.user_id', auth()->id())
                ->whereNull('s.fingerprint_id')
                ->distinct()
                ->pluck('s.id'))
            ->findOrFail($validated['student_id']);

        $reservations[$validated['fingerprint_id']] = [
            'student_id' => $student->id,
            'student_name' => $student->student_name,
            'device_uid' => $device->device_uid,
            'reserved_at' => now()->toDateTimeString(),
        ];

        Cache::put($this->fingerprintReservationCacheKey(), $reservations, now()->addMinutes(30));

        Cache::put('esp32:enrollment:' . $device->device_uid, [
            'student_id' => $student->id,
            'student_name' => $student->student_name,
            'fingerprint_id' => $validated['fingerprint_id'],
        ], now()->addMinutes(30));

        return back()->with('success', 'Enrollment request queued. Now place the finger on the ESP32.');
    }

    private function fingerprintReservationCacheKey(): string
    {
        return 'esp32:fingerprint:reservations';
    }
}
