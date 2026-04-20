<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\ClassTiming;
use App\Models\ModuleDistribution;
use App\Models\Student;
use App\Models\Week;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AttendanceController extends Controller
{
    public function index(Request $request)
    {
        $lecturer_id = auth()->id();
        $hasAttendanceSource = Schema::hasColumn('attendances', 'attendance_source');
        $hasAdminNumber = Schema::hasColumn('students', 'admin_number');
        $perPage = 10;

        $selectedWeek = $request->week_id;
        $selectedCourse = $request->course_id;
        $selectedDay = $request->day;
        $selectedSubject = $request->subject;

        $weeks = DB::table('weeks')->orderBy('id')->get();

        $courses = DB::table('module_distributions as md')
            ->join('modules as m', 'md.module_id', '=', 'm.id')
            ->join('programs as p', 'm.program_id', '=', 'p.id')
            ->where('md.user_id', $lecturer_id)
            ->select('p.id', 'p.program_name')
            ->distinct()
            ->orderBy('p.program_name')
            ->get();

        $days = DB::table('class_timings')
            ->join('module_distributions as md', 'class_timings.module_distribution_id', '=', 'md.id')
            ->join('modules as m', 'md.module_id', '=', 'm.id')
            ->when($selectedCourse, fn ($query) => $query->where('m.program_id', $selectedCourse))
            ->where('md.user_id', $lecturer_id)
            ->select('day')
            ->distinct()
            ->orderBy('day')
            ->get();

        $subjectsQuery = DB::table('module_distributions as md')
            ->join('modules as m', 'md.module_id', '=', 'm.id')
            ->where('md.user_id', $lecturer_id);

        if ($selectedCourse) {
            $subjectsQuery->where('m.program_id', $selectedCourse);
        }

        if ($selectedDay) {
            $subjectsQuery
                ->join('class_timings as ct', 'ct.module_distribution_id', '=', 'md.id')
                ->where('ct.day', $selectedDay);
        }

        $subjects = $subjectsQuery
            ->select('m.module_name as subject')
            ->distinct()
            ->orderBy('m.module_name')
            ->get();

        $students = collect();
        $classAdminNumbers = collect();

        if ($selectedWeek && $selectedCourse && $selectedDay && $selectedSubject) {
            $classContext = $this->resolveClassContext($lecturer_id, $selectedCourse, $selectedDay, $selectedSubject);

            if (! $classContext) {
                return view('lecturer.attendance', compact(
                    'students',
                    'classAdminNumbers',
                    'weeks',
                    'courses',
                    'days',
                    'subjects',
                    'selectedWeek',
                    'selectedCourse',
                    'selectedDay',
                    'selectedSubject'
                ));
            }

            $classAdminNumbers = DB::table('class_timings as ct')
                ->join('module_distributions as md', 'ct.module_distribution_id', '=', 'md.id')
                ->join('modules as m', 'md.module_id', '=', 'm.id')
                ->join('programs as p', 'm.program_id', '=', 'p.id')
                ->join('students as s', 'p.id', '=', 's.program_id')
                ->where('md.user_id', $lecturer_id)
                ->where('ct.id', $classContext->class_timing_id)
                ->where('md.id', $classContext->module_distribution_id)
                ->when($hasAdminNumber, fn ($query) => $query->whereNotNull('s.admin_number'))
                ->select('s.admin_number')
                ->distinct()
                ->orderBy('s.admin_number')
                ->pluck('s.admin_number');

            $students = DB::table('class_timings as ct')
                ->join('module_distributions as md', 'ct.module_distribution_id', '=', 'md.id')
                ->join('modules as m', 'md.module_id', '=', 'm.id')
                ->join('programs as p', 'm.program_id', '=', 'p.id')
                ->join('students as s', 'p.id', '=', 's.program_id')
                ->join('users as u', 's.user_id', '=', 'u.id')
                ->leftJoin('attendances as a', function ($join) use ($hasAttendanceSource, $selectedWeek) {
                    $join->on('ct.id', '=', 'a.class_timing_id')
                        ->on('s.id', '=', 'a.student_id')
                        ->on('md.id', '=', 'a.module_distribution_id')
                        ->where('a.week_id', $selectedWeek);

                    if ($hasAttendanceSource) {
                        $join->where(function ($sourceQuery) {
                            $sourceQuery->where('a.attendance_source', 'manual')
                                ->orWhereNull('a.attendance_source');
                        });
                    }
                })
                ->where('md.user_id', $lecturer_id)
                ->where('ct.id', $classContext->class_timing_id)
                ->where('md.id', $classContext->module_distribution_id)
                ->select(
                    'u.name as student_name',
                    's.id as student_id',
                    'ct.id as class_timing_id',
                    'ct.day',
                    'ct.time',
                    'ct.room',
                    'm.module_name as subject',
                    'md.id as module_distribution_id',
                    'a.id as attendance_id',
                    'a.is_present as is_present'
                )
                ->when($hasAdminNumber, fn ($query) => $query->addSelect('s.admin_number'))
                ->when(! $hasAdminNumber, fn ($query) => $query->addSelect(DB::raw('NULL as admin_number')))
                ->whereNull('a.id')
                ->orderBy('ct.day')
                ->orderBy('ct.time')
                ->orderBy('u.name')
                ->paginate($perPage)
                ->withQueryString();
        }

        return view('lecturer.attendance', compact(
            'students',
            'classAdminNumbers',
            'weeks',
            'courses',
            'days',
            'subjects',
            'selectedWeek',
            'selectedCourse',
            'selectedDay',
            'selectedSubject'
        ));
    }

    public function quickMark(Request $request)
    {
        if (! Schema::hasColumn('students', 'admin_number')) {
            return back()->with('error', 'Admin number search is not available yet.');
        }

        $validated = $request->validate([
            'week_id' => 'required|exists:weeks,id',
            'course_id' => 'required|exists:programs,id',
            'day' => 'required|string',
            'subject' => 'required|string',
            'admin_number' => 'required|string|max:50',
        ]);

        $lecturerId = auth()->id();
        $hasAttendanceSource = Schema::hasColumn('attendances', 'attendance_source');
        $adminNumber = trim((string) $validated['admin_number']);

        $classContext = $this->resolveClassContext(
            $lecturerId,
            $validated['course_id'],
            $validated['day'],
            $validated['subject']
        );

        if (! $classContext) {
            return back()->with('error', 'Class context not found for the selected filters.');
        }

        $studentQuery = Student::query()
            ->where('program_id', $validated['course_id'])
            ->whereNotNull('admin_number');

        $student = (clone $studentQuery)
            ->where('admin_number', $adminNumber)
            ->first();

        if (! $student) {
            return back()->with('error', 'Student not found for that admin number.');
        }

        $attributes = [
            'student_id' => $student->id,
            'module_distribution_id' => $classContext->module_distribution_id,
            'class_timing_id' => $classContext->class_timing_id,
            'week_id' => $validated['week_id'],
        ];

        if ($hasAttendanceSource) {
            $attributes['attendance_source'] = 'manual';
        }

        $values = [
            'academic_year' => date('Y'),
            'date' => now()->toDateString(),
            'is_present' => 1,
            'updated_at' => now(),
            'created_at' => now(),
        ];

        DB::table('attendances')->updateOrInsert($attributes, $values);

        return redirect()->route('attendanceindex', [
            'week_id' => $validated['week_id'],
            'course_id' => $validated['course_id'],
            'day' => $validated['day'],
            'subject' => $validated['subject'],
        ])->with('success', $student->student_name . ' marked present successfully.')
          ->with('attendance_sound', 'present');
    }

    public function store(Request $request)
    {
        if (!$request->has('attendance')) {
            return back()->with('error', 'No attendance data');
        }

        $hasAttendanceSource = Schema::hasColumn('attendances', 'attendance_source');

        foreach ($request->attendance as $student_id => $data) {
            if (!isset($data['status']) || $data['status'] === '') {
                continue;
            }

            $is_present = $data['status'] === 'present' ? 1 : 0;

            $attributes = [
                'student_id' => $student_id,
                'module_distribution_id' => $data['module_distribution_id'],
                'class_timing_id' => $data['class_timing_id'],
                'week_id' => $data['week_id'],
            ];

            if ($hasAttendanceSource) {
                $attributes['attendance_source'] = 'manual';
            }

            $values = [
                'academic_year' => date('Y'),
                'date' => now()->toDateString(),
                'is_present' => $is_present,
                'updated_at' => now(),
                'created_at' => now(),
            ];

            DB::table('attendances')->updateOrInsert($attributes, $values);
        }

        return back()->with('success', 'Attendance saved successfully');
    }

    public function recordsIndex(Request $request)
    {
        $hasAttendanceSource = Schema::hasColumn('attendances', 'attendance_source');

        $attendances = Attendance::with([
            'student.user',
            'moduleDistribution.module',
            'classTiming',
            'week',
        ])
            ->when($request->filled('search'), function ($query) use ($request) {
                $search = $request->search;
                $query->where(function ($innerQuery) use ($search) {
                    $innerQuery->where('academic_year', 'like', "%{$search}%")
                        ->orWhere('date', 'like', "%{$search}%")
                        ->orWhereHas('student', fn ($studentQuery) => $studentQuery->where('student_name', 'like', "%{$search}%"))
                        ->orWhereHas('moduleDistribution.module', fn ($moduleQuery) => $moduleQuery->where('module_name', 'like', "%{$search}%"));
                });
            })
            ->when($hasAttendanceSource, function ($query) {
                $query->where(function ($innerQuery) {
                    $innerQuery->where('attendance_source', 'manual')
                        ->orWhereNull('attendance_source');
                });
            })
            ->latest()
            ->paginate(15);

        return view('management.attendance.index', compact('attendances'));
    }

    public function recordsCreate()
    {
        return view('management.attendance.create', $this->attendanceFormData());
    }

    public function recordsStore(Request $request)
    {
        $validated = $this->validateAttendanceRecord($request);
        if (Schema::hasColumn('attendances', 'attendance_source')) {
            $validated['attendance_source'] = 'manual';
        }

        Attendance::create($validated);

        return redirect()->route('attendance.records.index')->with('success', 'Attendance record created successfully.');
    }

    public function recordsEdit(Attendance $attendance)
    {
        return view('management.attendance.edit', array_merge(
            $this->attendanceFormData(),
            compact('attendance')
        ));
    }

    public function recordsUpdate(Request $request, Attendance $attendance)
    {
        $validated = $this->validateAttendanceRecord($request);
        if (Schema::hasColumn('attendances', 'attendance_source')) {
            $validated['attendance_source'] = 'manual';
        }

        $attendance->update($validated);

        return redirect()->route('attendance.records.index')->with('success', 'Attendance record updated successfully.');
    }

    public function recordsDestroy(Attendance $attendance)
    {
        $attendance->delete();

        return redirect()->route('attendance.records.index')->with('success', 'Attendance record deleted successfully.');
    }

    private function attendanceFormData(): array
    {
        return [
            'students' => Student::with('user')->orderBy('student_name')->get(),
            'moduleDistributions' => ModuleDistribution::with('module')->get(),
            'classTimings' => $this->hasAttendanceColumn('class_timing_id')
                ? ClassTiming::orderBy('day')->orderBy('time')->get()
                : collect(),
            'weeks' => $this->hasAttendanceColumn('week_id')
                ? Week::orderBy('id')->get()
                : collect(),
            'hasClassTiming' => $this->hasAttendanceColumn('class_timing_id'),
            'hasWeek' => $this->hasAttendanceColumn('week_id'),
        ];
    }

    private function validateAttendanceRecord(Request $request): array
    {
        $rules = [
            'module_distribution_id' => 'required|exists:module_distributions,id',
            'student_id' => 'required|exists:students,id',
            'academic_year' => 'required|string|max:20',
            'date' => 'required|date',
            'is_present' => 'required|boolean',
        ];

        if ($this->hasAttendanceColumn('class_timing_id')) {
            $rules['class_timing_id'] = 'nullable|exists:class_timings,id';
        }

        if ($this->hasAttendanceColumn('week_id')) {
            $rules['week_id'] = 'nullable|exists:weeks,id';
        }

        return $request->validate($rules);
    }

    private function hasAttendanceColumn(string $column): bool
    {
        return Schema::hasColumn('attendances', $column);
    }

    private function resolveClassContext(int $lecturerId, string $courseId, string $day, string $subject): ?object
    {
        return DB::table('class_timings as ct')
            ->join('module_distributions as md', 'ct.module_distribution_id', '=', 'md.id')
            ->join('modules as m', 'md.module_id', '=', 'm.id')
            ->where('md.user_id', $lecturerId)
            ->where('m.program_id', $courseId)
            ->where('ct.day', $day)
            ->where('m.module_name', $subject)
            ->select(
                'ct.id as class_timing_id',
                'md.id as module_distribution_id',
                'm.program_id'
            )
            ->first();
    }
}
