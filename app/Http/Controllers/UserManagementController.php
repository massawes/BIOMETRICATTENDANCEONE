<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Hod;
use App\Models\Lecturer;
use App\Models\Program;
use App\Models\Role;
use App\Models\Student;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class UserManagementController extends Controller
{
    public function hodIndex(Request $request)
    {
        $departmentId = $this->departmentId();

        $users = $this->departmentUsersQuery()
            ->when($request->filled('search'), function ($query) use ($request) {
                $search = $request->search;
                $query->where(function ($innerQuery) use ($search) {
                    $innerQuery->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhereHas('role', fn ($roleQuery) => $roleQuery->where('name', 'like', "%{$search}%"))
                        ->orWhereHas('program', fn ($programQuery) => $programQuery->where('program_name', 'like', "%{$search}%"))
                        ->orWhereHas('department', fn ($departmentQuery) => $departmentQuery->where('department_name', 'like', "%{$search}%"));
                });
            })
            ->orderBy('name')
            ->paginate(12);

        $assignableUsers = $this->departmentUsersQuery()
            ->orderBy('name')
            ->get();

        $roles = Role::orderBy('name')->get();
        $programs = Program::where('department_id', $departmentId)
            ->orderBy('program_name')
            ->get();

        return view('management.users.hod', compact('users', 'assignableUsers', 'roles', 'programs'));
    }

    public function hodAssignRole(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'role_id' => 'required|exists:roles,id',
            'program_id' => 'nullable|exists:programs,id',
            'admin_number' => 'nullable|string|max:50',
        ]);

        $user = $this->departmentUsersQuery()->findOrFail($validated['user_id']);

        abort_if($user->id === auth()->id(), 403, 'You cannot change your own role here.');

        $role = Role::findOrFail($validated['role_id']);
        $roleName = $role->name;
        $departmentId = $this->departmentId();
        $programId = $this->resolveProgramIdForRole($roleName, $validated['program_id'] ?? null, $departmentId);

        if ($roleName === 'student') {
            if (empty($validated['admin_number'])) {
                throw ValidationException::withMessages([
                    'admin_number' => 'Admin number is required for student role.',
                ]);
            }

            $adminNumberExists = Student::query()
                ->where('admin_number', $validated['admin_number'])
                ->where('user_id', '<>', $user->id)
                ->exists();

            if ($adminNumberExists) {
                throw ValidationException::withMessages([
                    'admin_number' => 'This admin number is already assigned to another student.',
                ]);
            }
        }

        $user->update([
            'role_id' => $role->id,
            'program_id' => $programId,
            'department_id' => $roleName === 'student' ? null : $departmentId,
        ]);

        $this->syncStudentRecord($user, $roleName, $programId, $validated['admin_number'] ?? null);
        $this->syncLecturerRecord($user, $roleName, $departmentId);
        $this->syncHodRecord($user, $roleName, $departmentId);

        return redirect()->route('hod.users.index')->with('success', 'User role assigned successfully.');
    }

    public function index(Request $request)
    {
        $users = User::with(['role', 'program', 'department'])
            ->when($request->filled('search'), function ($query) use ($request) {
                $search = $request->search;
                $query->where(function ($innerQuery) use ($search) {
                    $innerQuery->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhereHas('role', fn ($roleQuery) => $roleQuery->where('name', 'like', "%{$search}%"))
                        ->orWhereHas('program', fn ($programQuery) => $programQuery->where('program_name', 'like', "%{$search}%"))
                        ->orWhereHas('department', fn ($departmentQuery) => $departmentQuery->where('department_name', 'like', "%{$search}%"));
                });
            })
            ->paginate(15);

        return view('management.users.index', compact('users'));
    }

    public function export(Request $request)
    {
        $users = User::with(['role', 'program', 'department'])
            ->when($request->filled('search'), function ($query) use ($request) {
                $search = $request->search;
                $query->where(function ($innerQuery) use ($search) {
                    $innerQuery->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhereHas('role', fn ($roleQuery) => $roleQuery->where('name', 'like', "%{$search}%"))
                        ->orWhereHas('program', fn ($programQuery) => $programQuery->where('program_name', 'like', "%{$search}%"))
                        ->orWhereHas('department', fn ($departmentQuery) => $departmentQuery->where('department_name', 'like', "%{$search}%"));
                });
            })
            ->orderBy('name')
            ->get();

        return response()->json([
            'sheet_name' => 'Users',
            'filename' => 'users-export.xlsx',
            'rows' => $users->map(fn ($user) => [
                'name' => $user->name,
                'email' => $user->email,
                'role_name' => $user->role?->name,
                'program_name' => $user->program?->program_name,
                'program_id' => $user->program_id,
                'department_name' => $user->department?->department_name,
                'department_id' => $user->department_id,
            ])->values(),
        ]);
    }

    public function create()
    {
        return view('management.users.create', [
            'roles' => Role::orderBy('name')->get(),
            'programs' => Program::orderBy('program_name')->get(),
            'departments' => Department::orderBy('department_name')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8',
            'role_id' => 'required|exists:roles,id',
            'program_id' => 'nullable|exists:programs,id',
            'department_id' => 'nullable|exists:departments,id',
        ]);

        $validated['password'] = Hash::make($validated['password']);

        User::create($validated);

        return redirect()->route('users.index')->with('success', 'User created successfully.');
    }

    public function edit(User $user)
    {
        return view('management.users.edit', [
            'user' => $user,
            'roles' => Role::orderBy('name')->get(),
            'programs' => Program::orderBy('program_name')->get(),
            'departments' => Department::orderBy('department_name')->get(),
        ]);
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8',
            'role_id' => 'required|exists:roles,id',
            'program_id' => 'nullable|exists:programs,id',
            'department_id' => 'nullable|exists:departments,id',
        ]);

        if (!empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        $user->update($validated);

        return redirect()->route('users.index')->with('success', 'User updated successfully.');
    }

    public function destroy(User $user)
    {
        $user->delete();

        return redirect()->route('users.index')->with('success', 'User deleted successfully.');
    }

    private function departmentUsersQuery()
    {
        $departmentId = $this->departmentId();

        return User::with(['role', 'program.department', 'department'])
            ->where(function ($query) use ($departmentId) {
                $query->where('department_id', $departmentId)
                    ->orWhereHas('program', function ($programQuery) use ($departmentId) {
                        $programQuery->where('department_id', $departmentId);
                    });
            });
    }

    private function departmentId(): ?int
    {
        return auth()->user()->hod->department_id ?? auth()->user()->department_id;
    }

    private function resolveProgramIdForRole(string $roleName, ?int $programId, int $departmentId): ?int
    {
        if ($roleName !== 'student') {
            return null;
        }

        abort_unless($programId, 422, 'Program is required for student role.');

        abort_unless(
            Program::where('id', $programId)->where('department_id', $departmentId)->exists(),
            403,
            'Selected program does not belong to your department.'
        );

        return $programId;
    }

    private function syncStudentRecord(User $user, string $roleName, ?int $programId, ?string $adminNumber = null): void
    {
        if ($roleName === 'student') {
            Student::updateOrCreate(
                ['user_id' => $user->id],
                [
                    'student_name' => $user->name,
                    'admin_number' => $adminNumber ?? $user->student->admin_number ?? null,
                    'intake' => $user->student->intake ?? now()->year,
                    'program_id' => $programId,
                ]
            );

            return;
        }

        Student::where('user_id', $user->id)->delete();
    }

    private function syncLecturerRecord(User $user, string $roleName, int $departmentId): void
    {
        if ($roleName === 'lecturer') {
            Lecturer::updateOrCreate(
                ['user_id' => $user->id],
                [
                    'lecturer_name' => $user->name,
                    'department_id' => $departmentId,
                ]
            );

            return;
        }

        Lecturer::where('user_id', $user->id)->delete();
    }

    private function syncHodRecord(User $user, string $roleName, int $departmentId): void
    {
        if ($roleName === 'HOD') {
            Hod::updateOrCreate(
                ['user_id' => $user->id],
                [
                    'hod_name' => $user->name,
                    'department_id' => $departmentId,
                ]
            );

            return;
        }

        Hod::where('user_id', $user->id)->delete();
    }
}
