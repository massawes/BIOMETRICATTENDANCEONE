<?php

namespace App\Http\Controllers;

use App\Models\Program;
use App\Models\Role;
use App\Models\Student;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class StudentsController extends Controller
{
    public function index(Request $request)
    {
        $students = $this->studentsQuery($request)
            ->paginate(10);

        return view('management.students.index', compact('students'));
    }

    public function export(Request $request)
    {
        $students = $this->studentsQuery($request)
            ->orderBy('student_name')
            ->get();

        return response()->json([
            'sheet_name' => 'Students',
            'filename' => 'students-export.xlsx',
            'rows' => $students->map(fn ($student) => [
                'student_name' => $student->student_name,
                'admin_number' => $student->admin_number,
                'email' => $student->user?->email,
                'intake' => $student->intake,
                'program_name' => $student->program?->program_name,
                'program_id' => $student->program_id,
                'fingerprint_id' => $student->fingerprint_id,
            ])->values(),
        ]);
    }

    public function create()
    {
        $programs = Program::where('department_id', $this->departmentId())->get();
        $assignedFingerprints = $this->assignedFingerprints();

        return view('management.students.create', compact('programs', 'assignedFingerprints'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'student_name' => 'required|string|max:255',
            'admin_number' => 'required|string|max:50|unique:students,admin_number',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8',
            'intake' => 'required|digits:4',
            'program_id' => 'required|exists:programs,id',
            'fingerprint_id' => 'nullable|integer|min:1|max:127|unique:students,fingerprint_id',
        ]);

        abort_unless(
            Program::where('id', $request->program_id)->where('department_id', $this->departmentId())->exists(),
            403
        );

        DB::transaction(function () use ($request) {
            $user = User::create([
                'name' => $request->student_name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role_id' => Role::where('name', 'student')->first()->id ?? 1,
                'program_id' => $request->program_id,
            ]);

            Student::create([
                'student_name' => $request->student_name,
                'admin_number' => $request->admin_number,
                'intake' => $request->intake,
                'user_id' => $user->id,
                'program_id' => $request->program_id,
                'fingerprint_id' => $request->fingerprint_id,
            ]);
        });

        return redirect()->route('students.index')->with('success', 'Student created successfully.');
    }

    public function edit($id)
    {
        $student = Student::with('user')
            ->whereHas('program', function ($query) {
                $query->where('department_id', $this->departmentId());
            })
            ->findOrFail($id);
        $programs = Program::where('department_id', $this->departmentId())->get();
        $assignedFingerprints = $this->assignedFingerprints($student->id);

        return view('management.students.edit', compact('student', 'programs', 'assignedFingerprints'));
    }

    public function update(Request $request, $id)
    {
        $student = Student::whereHas('program', function ($query) {
            $query->where('department_id', $this->departmentId());
        })->findOrFail($id);
        $user = $student->user;

        $request->validate([
            'student_name' => 'required|string|max:255',
            'admin_number' => 'required|string|max:50|unique:students,admin_number,' . $student->id,
            'email' => 'required|email|unique:users,email,' . $user->id,
            'intake' => 'required|digits:4',
            'program_id' => 'required|exists:programs,id',
            'password' => 'nullable|string|min:8',
            'fingerprint_id' => 'nullable|integer|min:1|max:127|unique:students,fingerprint_id,' . $student->id,
        ], [
            'fingerprint_id.unique' => 'This fingerprint ID is already assigned to another student.',
        ]);

        abort_unless(
            Program::where('id', $request->program_id)->where('department_id', $this->departmentId())->exists(),
            403
        );

        $user->update([
            'name' => $request->student_name,
            'email' => $request->email,
            'program_id' => $request->program_id,
        ]);

        if ($request->filled('password')) {
            $user->update(['password' => Hash::make($request->password)]);
        }

        $student->update([
            'student_name' => $request->student_name,
            'admin_number' => $request->admin_number,
            'intake' => $request->intake,
            'program_id' => $request->program_id,
            'fingerprint_id' => $request->fingerprint_id,
        ]);

        return redirect()->route('students.index')->with('success', 'Student updated successfully.');
    }

    public function destroy($id)
    {
        $student = Student::whereHas('program', function ($query) {
            $query->where('department_id', $this->departmentId());
        })->findOrFail($id);
        optional($student->user)->delete();

        return redirect()->route('students.index')->with('success', 'Student deleted successfully.');
    }

    private function departmentId(): ?int
    {
        return auth()->user()->hod->department_id ?? auth()->user()->department_id;
    }

    private function assignedFingerprints(?int $ignoreStudentId = null)
    {
        return Student::query()
            ->whereHas('program', function ($query) {
                $query->where('department_id', $this->departmentId());
            })
            ->when($ignoreStudentId, fn ($query) => $query->where('id', '<>', $ignoreStudentId))
            ->whereNotNull('fingerprint_id')
            ->orderBy('fingerprint_id')
            ->get(['student_name', 'fingerprint_id']);
    }

    private function studentsQuery(Request $request)
    {
        return Student::with(['user', 'program'])
            ->whereHas('program', function ($query) {
                $query->where('department_id', $this->departmentId());
            })
            ->when($request->filled('search'), function ($query) use ($request) {
                $search = $request->search;
                $query->where(function ($innerQuery) use ($search) {
                    $innerQuery->where('student_name', 'like', "%{$search}%")
                        ->orWhere('admin_number', 'like', "%{$search}%")
                        ->orWhere('intake', 'like', "%{$search}%")
                        ->orWhereHas('user', fn ($userQuery) => $userQuery->where('email', 'like', "%{$search}%"))
                        ->orWhereHas('program', fn ($programQuery) => $programQuery->where('program_name', 'like', "%{$search}%"));
                });
            });
    }
}
