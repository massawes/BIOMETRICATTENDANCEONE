<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role;
use App\Models\Program;
use App\Models\Student;
use App\Models\Lecturer;
use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use App\Models\Hod;
use Illuminate\Validation\ValidationException;

class RegisteredUserController extends Controller
{
    /**
     * Show registration form
     */
    public function create(): View
    {
        $roles = Role::all();
        $programs = Program::all();
        $departments = Department::all();

        return view('auth.register', compact('roles', 'programs', 'departments'));
    }

    /**
     * Handle registration
     */
    public function store(Request $request): RedirectResponse
    {
        // ✅ VALIDATION
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'password' => 'required|confirmed|min:6',
            'role_id' => 'required|exists:roles,id',
            'program_id' => 'nullable|exists:programs,id',
            'admin_number' => 'nullable|string|max:50|unique:students,admin_number',
            'department_id' => 'nullable|exists:departments,id',
        ]);

        $role = Role::findOrFail($request->role_id);
        $roleName = strtolower($role->name);

        if ($roleName === 'student') {
            if (! $request->filled('program_id')) {
                throw ValidationException::withMessages([
                    'program_id' => 'Program is required for student account.',
                ]);
            }

            if (! $request->filled('admin_number')) {
                throw ValidationException::withMessages([
                    'admin_number' => 'Admin number is required for student account.',
                ]);
            }
        }

        if (in_array($roleName, ['lecturer', 'hod'], true) && ! $request->filled('department_id')) {
            throw ValidationException::withMessages([
                'department_id' => 'Department is required for this role.',
            ]);
        }

        try {
            DB::beginTransaction();

            // ✅ CREATE USER
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role_id' => $request->role_id,
                'program_id' => $roleName === 'student' ? $request->program_id : null,
                'department_id' => in_array($roleName, ['lecturer', 'hod'], true) ? $request->department_id : null,
            ]);

            // ✅ STUDENT
            if ($roleName === 'student') {
                Student::create([
                    'student_name' => $user->name,
                    'admin_number' => $request->admin_number,
                    'user_id' => $user->id,
                    'program_id' => $request->program_id,
                    'intake' => now()->year,
                ]);
            }

            // ✅ LECTURER
            if ($roleName === 'lecturer') {
                Lecturer::create([
                    'user_id' => $user->id,
                    'lecturer_name' => $user->name,
                    'department_id' => $request->department_id,
                ]);
            }

            // ✅ HOD
            if ($roleName === 'hod') {
                Hod::create([
                    'user_id' => $user->id,
                    'hod_name' => $user->name,
                    'department_id' => $request->department_id,
                ]);
            }

            DB::commit();

            return redirect()
                ->route('login')
                ->with('success', 'Account created successfully');
        } catch (\Exception $e) {
            DB::rollBack();

            return back()->withInput()->withErrors([
                'error' => 'Something went wrong: ' . $e->getMessage()
            ]);
        }
    }
}
