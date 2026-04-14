<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\ClassTiming;
use App\Models\Department;
use App\Models\Lecturer;
use App\Models\Module;
use App\Models\ModuleDistribution;
use App\Models\Program;
use App\Models\Role;
use App\Models\Student;
use App\Models\User;
use App\Models\Week;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

class SpreadsheetController extends Controller
{
    public function import(Request $request, string $entity)
    {
        $validated = $request->validate([
            'rows' => ['required', 'array', 'min:1'],
            'rows.*' => ['array'],
        ]);

        $rows = collect($validated['rows'])
            ->map(fn ($row) => $this->normalizeRow((array) $row))
            ->filter(fn ($row) => ! $this->isEmptyRow($row))
            ->values()
            ->all();

        if (! count($rows)) {
            return response()->json([
                'message' => 'The selected file does not contain usable rows.',
            ], 422);
        }

        $result = match ($entity) {
            'departments' => $this->importDepartments($rows),
            'weeks' => $this->importWeeks($rows),
            'programs' => $this->importPrograms($rows),
            'modules' => $this->importModules($rows),
            'lecturers' => $this->importLecturers($rows),
            'hods' => $this->importHods($rows),
            'students' => $this->importStudents($rows),
            'users' => $this->importUsers($rows),
            'class_timings' => $this->importClassTimings($rows),
            'roles' => $this->importRoles($rows),
            'attendance_records' => $this->importAttendanceRecords($rows),
            'module_distributions' => $this->importModuleDistributions($rows),
            default => null,
        };

        if ($result === null) {
            return response()->json([
                'message' => 'Unsupported import type.',
            ], 404);
        }

        return response()->json([
            'message' => $result['message'],
            'created' => $result['created'],
            'updated' => $result['updated'],
            'skipped' => $result['skipped'],
            'errors' => $result['errors'],
            'redirect_url' => $this->redirectFor($entity),
        ]);
    }

    private function importDepartments(array $rows): array
    {
        abort_unless($this->roleName() === 'director_academic', 403);

        $created = 0;
        $skipped = 0;
        $errors = [];

        foreach ($rows as $index => $row) {
            $name = $this->stringValue($row, 'department_name');

            if (! $name) {
                $errors[] = $this->rowError($index, 'department_name is required.');
                continue;
            }

            $department = Department::firstOrCreate(['department_name' => $name]);
            $department->wasRecentlyCreated ? $created++ : $skipped++;
        }

        return $this->resultMessage('Department', $created, 0, $skipped, $errors);
    }

    private function importWeeks(array $rows): array
    {
        abort_unless(in_array($this->roleName(), ['hod', 'director_academic']), 403);

        $created = 0;
        $skipped = 0;
        $errors = [];

        foreach ($rows as $index => $row) {
            $name = $this->stringValue($row, 'week_name');

            if (! $name) {
                $errors[] = $this->rowError($index, 'week_name is required.');
                continue;
            }

            $week = Week::firstOrCreate(['week_name' => $name]);
            $week->wasRecentlyCreated ? $created++ : $skipped++;
        }

        return $this->resultMessage('Week', $created, 0, $skipped, $errors);
    }

    private function importPrograms(array $rows): array
    {
        abort_unless(in_array($this->roleName(), ['hod', 'director_academic']), 403);

        $departmentId = $this->departmentId();
        $created = 0;
        $skipped = 0;
        $errors = [];

        foreach ($rows as $index => $row) {
            $name = $this->stringValue($row, 'program_name');

            if (! $name) {
                $errors[] = $this->rowError($index, 'program_name is required.');
                continue;
            }

            $program = Program::firstOrCreate([
                'department_id' => $departmentId,
                'program_name' => $name,
            ]);

            $program->wasRecentlyCreated ? $created++ : $skipped++;
        }

        return $this->resultMessage('Program', $created, 0, $skipped, $errors);
    }

    private function importModules(array $rows): array
    {
        abort_unless(in_array($this->roleName(), ['hod', 'director_academic']), 403);

        $departmentId = $this->departmentId();
        $created = 0;
        $updated = 0;
        $skipped = 0;
        $errors = [];

        foreach ($rows as $index => $row) {
            $moduleName = $this->stringValue($row, 'module_name');
            $moduleCode = $this->stringValue($row, 'module_code');
            $program = $this->resolveProgramFromRow($row, $departmentId);

            if (! $moduleName || ! $moduleCode || ! $program) {
                $errors[] = $this->rowError($index, 'module_name, module_code, and program_id/program_name are required.');
                continue;
            }

            $module = Module::updateOrCreate(
                ['module_code' => $moduleCode],
                [
                    'module_name' => $moduleName,
                    'module_credit' => $this->integerValue($row, 'module_credit') ?? 0,
                    'semester' => $this->stringValue($row, 'semester') ?? '',
                    'nta_level' => $this->stringValue($row, 'nta_level') ?? '',
                    'program_id' => $program->id,
                ]
            );

            $module->wasRecentlyCreated ? $created++ : $updated++;
        }

        return $this->resultMessage('Module', $created, $updated, $skipped, $errors);
    }

    private function importLecturers(array $rows): array
    {
        abort_unless(in_array($this->roleName(), ['hod', 'director_academic']), 403);

        $departmentId = $this->departmentId();
        $roleId = $this->roleId('lecturer');
        $created = 0;
        $updated = 0;
        $skipped = 0;
        $errors = [];

        foreach ($rows as $index => $row) {
            $name = $this->stringValue($row, 'lecturer_name');
            $email = $this->stringValue($row, 'email');

            if (! $name || ! $email) {
                $errors[] = $this->rowError($index, 'lecturer_name and email are required.');
                continue;
            }

            DB::transaction(function () use ($row, $name, $email, $departmentId, $roleId, &$created, &$updated) {
                $user = User::firstOrNew(['email' => $email]);
                $user->fill([
                    'name' => $name,
                    'password' => Hash::make($this->stringValue($row, 'password') ?: '12345678'),
                    'role_id' => $roleId,
                    'department_id' => $departmentId,
                ]);
                $user->save();

                Lecturer::updateOrCreate(
                    ['user_id' => $user->id],
                    [
                        'lecturer_name' => $name,
                        'department_id' => $departmentId,
                    ]
                );

                $user->wasRecentlyCreated ? $created++ : $updated++;
            });
        }

        return $this->resultMessage('Lecturer', $created, $updated, $skipped, $errors);
    }

    private function importHods(array $rows): array
    {
        abort_unless($this->roleName() === 'director_academic', 403);

        $roleId = $this->roleId('HOD') ?? $this->roleId('hod');
        $created = 0;
        $updated = 0;
        $skipped = 0;
        $errors = [];

        foreach ($rows as $index => $row) {
            $name = $this->stringValue($row, 'hod_name') ?: $this->stringValue($row, 'name');
            $email = $this->stringValue($row, 'email');
            $department = $this->resolveDepartmentFromRow($row);

            if (! $name || ! $email || ! $department) {
                $errors[] = $this->rowError($index, 'hod_name, email, and department_id/department_name are required.');
                continue;
            }

            DB::transaction(function () use ($row, $name, $email, $department, $roleId, &$created, &$updated) {
                $password = $this->stringValue($row, 'password') ?: '12345678';

                $user = User::firstOrNew(['email' => $email]);
                $user->fill([
                    'name' => $name,
                    'password' => Hash::make($password),
                    'role_id' => $roleId,
                    'department_id' => $department->id,
                ]);
                $user->save();

                Hod::updateOrCreate(
                    ['user_id' => $user->id],
                    [
                        'hod_name' => $name,
                        'department_id' => $department->id,
                    ]
                );

                $user->wasRecentlyCreated ? $created++ : $updated++;
            });
        }

        return $this->resultMessage('HOD', $created, $updated, $skipped, $errors);
    }

    private function importStudents(array $rows): array
    {
        abort_unless(in_array($this->roleName(), ['hod', 'lecturer', 'director_academic']), 403);

        $departmentId = $this->departmentId();
        $roleId = $this->roleId('student');
        $created = 0;
        $updated = 0;
        $skipped = 0;
        $errors = [];

        foreach ($rows as $index => $row) {
            $name = $this->stringValue($row, 'student_name');
            $adminNumber = $this->stringValue($row, 'admin_number');
            $email = $this->stringValue($row, 'email');
            $program = $this->resolveProgramFromRow($row, $departmentId);

            if (! $name || ! $adminNumber || ! $email || ! $program) {
                $errors[] = $this->rowError($index, 'student_name, admin_number, email, and program_id/program_name are required.');
                continue;
            }

            DB::transaction(function () use ($row, $name, $adminNumber, $email, $program, $roleId, &$created, &$updated) {
                $password = $this->stringValue($row, 'password') ?: '12345678';

                $user = User::firstOrNew(['email' => $email]);
                $user->fill([
                    'name' => $name,
                    'password' => Hash::make($password),
                    'role_id' => $roleId,
                    'program_id' => $program->id,
                    'department_id' => $program->department_id,
                ]);
                $user->save();

                Student::updateOrCreate(
                    ['admin_number' => $adminNumber],
                    [
                        'student_name' => $name,
                        'intake' => $this->integerValue($row, 'intake') ?? date('Y'),
                        'user_id' => $user->id,
                        'program_id' => $program->id,
                        'fingerprint_id' => $this->integerValue($row, 'fingerprint_id'),
                    ]
                );

                $user->wasRecentlyCreated ? $created++ : $updated++;
            });
        }

        return $this->resultMessage('Student', $created, $updated, $skipped, $errors);
    }

    private function importUsers(array $rows): array
    {
        abort_unless($this->roleName() === 'examination_officer', 403);

        $created = 0;
        $updated = 0;
        $skipped = 0;
        $errors = [];

        foreach ($rows as $index => $row) {
            $name = $this->stringValue($row, 'name');
            $email = $this->stringValue($row, 'email');
            $role = $this->resolveRoleFromRow($row);

            if (! $name || ! $email || ! $role) {
                $errors[] = $this->rowError($index, 'name, email, and role_id/role_name are required.');
                continue;
            }

            $roleName = strtolower($role->name);
            $department = null;
            $program = null;

            if (in_array($roleName, ['lecturer', 'hod'], true)) {
                $department = $this->resolveDepartmentFromRow($row);

                if (! $department) {
                    $errors[] = $this->rowError($index, 'department_id/department_name is required for lecturer and HOD roles.');
                    continue;
                }
            }

            if ($roleName === 'student') {
                $program = $this->resolveProgramFromRow($row, $department?->id);
                $adminNumber = $this->stringValue($row, 'admin_number');

                if (! $program || ! $adminNumber) {
                    $errors[] = $this->rowError($index, 'program_id/program_name and admin_number are required for student role.');
                    continue;
                }

                $department = Department::find($program->department_id);

                if (! $department) {
                    $errors[] = $this->rowError($index, 'The selected program does not have a valid department.');
                    continue;
                }
            }

            DB::transaction(function () use ($row, $name, $email, $role, $roleName, $department, $program, &$created, &$updated) {
                $password = $this->stringValue($row, 'password') ?: '12345678';
                $user = User::firstOrNew(['email' => $email]);

                $user->fill([
                    'name' => $name,
                    'password' => Hash::make($password),
                    'role_id' => $role->id,
                    'program_id' => $program?->id,
                    'department_id' => $department?->id,
                ]);
                $user->save();

                if ($roleName === 'student') {
                    $adminNumber = $this->stringValue($row, 'admin_number');

                    Student::updateOrCreate(
                        ['user_id' => $user->id],
                        [
                            'student_name' => $name,
                            'admin_number' => $adminNumber,
                            'intake' => $this->integerValue($row, 'intake') ?? date('Y'),
                            'program_id' => $program?->id,
                            'fingerprint_id' => $this->integerValue($row, 'fingerprint_id'),
                        ]
                    );
                } else {
                    Student::where('user_id', $user->id)->delete();
                }

                if ($roleName === 'lecturer') {
                    Lecturer::updateOrCreate(
                        ['user_id' => $user->id],
                        [
                            'lecturer_name' => $name,
                            'department_id' => $department?->id,
                        ]
                    );
                } else {
                    Lecturer::where('user_id', $user->id)->delete();
                }

                if ($roleName === 'hod') {
                    Hod::updateOrCreate(
                        ['user_id' => $user->id],
                        [
                            'hod_name' => $name,
                            'department_id' => $department?->id,
                        ]
                    );
                } else {
                    Hod::where('user_id', $user->id)->delete();
                }

                $user->wasRecentlyCreated ? $created++ : $updated++;
            });
        }

        return $this->resultMessage('User', $created, $updated, $skipped, $errors);
    }

    private function importClassTimings(array $rows): array
    {
        abort_unless(in_array($this->roleName(), ['hod', 'lecturer', 'director_academic']), 403);

        $departmentId = $this->departmentId();
        $created = 0;
        $updated = 0;
        $skipped = 0;
        $errors = [];
        $hasWeek = Schema::hasColumn('class_timings', 'week_id');

        foreach ($rows as $index => $row) {
            $moduleDistribution = $this->resolveModuleDistributionFromRow($row, $departmentId);

            if (! $moduleDistribution) {
                $errors[] = $this->rowError($index, 'module_distribution_id or module_code/academic_year is required.');
                continue;
            }

            $day = $this->stringValue($row, 'day');
            $time = $this->stringValue($row, 'time');
            $room = $this->stringValue($row, 'room');

            if (! $day || ! $time || ! $room) {
                $errors[] = $this->rowError($index, 'day, time, and room are required.');
                continue;
            }

            $attributes = [
                'module_distribution_id' => $moduleDistribution->id,
                'day' => $day,
                'time' => $time,
                'room' => $room,
            ];

            if ($hasWeek) {
                $attributes['week_id'] = $this->resolveWeekFromRow($row)?->id;
            }

            $classTiming = ClassTiming::updateOrCreate(
                $attributes,
                array_merge($attributes, [
                    'subject' => $this->stringValue($row, 'subject') ?: $moduleDistribution->module?->module_name,
                ])
            );

            $classTiming->wasRecentlyCreated ? $created++ : $updated++;
        }

        return $this->resultMessage('Class timing', $created, $updated, $skipped, $errors);
    }

    private function importAttendanceRecords(array $rows): array
    {
        abort_unless(in_array($this->roleName(), ['hod', 'lecturer', 'director_academic', 'registrar', 'examination_officer', 'quality_assurance', 'rector']), 403);

        $departmentId = $this->departmentId();
        $created = 0;
        $updated = 0;
        $skipped = 0;
        $errors = [];
        $hasWeek = Schema::hasColumn('attendances', 'week_id');
        $hasClassTiming = Schema::hasColumn('attendances', 'class_timing_id');
        $hasAttendanceSource = Schema::hasColumn('attendances', 'attendance_source');

        foreach ($rows as $index => $row) {
            $student = $this->resolveStudentFromRow($row, $departmentId);
            $moduleDistribution = $this->resolveModuleDistributionFromRow($row, $departmentId);

            if (! $student || ! $moduleDistribution) {
                $errors[] = $this->rowError($index, 'student_id/student_admin_number and module_distribution_id/module_code are required.');
                continue;
            }

            $attributes = [
                'student_id' => $student->id,
                'module_distribution_id' => $moduleDistribution->id,
            ];

            if ($hasClassTiming) {
                $attributes['class_timing_id'] = $this->integerValue($row, 'class_timing_id') ?: null;
            }

            if ($hasWeek) {
                $attributes['week_id'] = $this->resolveWeekFromRow($row)?->id;
            }

            $record = Attendance::updateOrCreate(
                $attributes,
                array_merge($attributes, [
                    'academic_year' => $this->stringValue($row, 'academic_year') ?: date('Y'),
                    'date' => $this->stringValue($row, 'date') ?: now()->toDateString(),
                    'is_present' => $this->boolValue($row, 'is_present', 'status') ? 1 : 0,
                    'attendance_source' => $hasAttendanceSource ? 'manual' : null,
                ])
            );

            $record->wasRecentlyCreated ? $created++ : $updated++;
        }

        return $this->resultMessage('Attendance record', $created, $updated, $skipped, $errors);
    }

    private function importModuleDistributions(array $rows): array
    {
        abort_unless(in_array($this->roleName(), ['hod', 'director_academic']), 403);

        $departmentId = $this->departmentId();
        $created = 0;
        $updated = 0;
        $skipped = 0;
        $errors = [];

        foreach ($rows as $index => $row) {
            $module = $this->resolveModuleFromRow($row, $departmentId);
            $lecturer = $this->resolveLecturerFromRow($row, $departmentId);
            $academicYear = $this->stringValue($row, 'academic_year');

            if (! $module || ! $lecturer || ! $academicYear) {
                $errors[] = $this->rowError($index, 'module_code/module_id, lecturer_name/user_id, and academic_year are required.');
                continue;
            }

            $distribution = ModuleDistribution::updateOrCreate(
                [
                    'module_id' => $module->id,
                    'academic_year' => $academicYear,
                ],
                [
                    'user_id' => $lecturer->id,
                ]
            );

            $distribution->wasRecentlyCreated ? $created++ : $updated++;
        }

        return $this->resultMessage('Module distribution', $created, $updated, $skipped, $errors);
    }

    private function resolveProgramFromRow(array $row, ?int $departmentId = null): ?Program
    {
        $programId = $this->integerValue($row, 'program_id');
        if ($programId) {
            return Program::query()
                ->when($departmentId, fn ($query) => $query->where('department_id', $departmentId))
                ->find($programId);
        }

        $programName = $this->stringValue($row, 'program_name');
        if (! $programName) {
            $programsQuery = Program::query()
                ->when($departmentId, fn ($query) => $query->where('department_id', $departmentId));

            $programsCount = (clone $programsQuery)->count();
            if ($programsCount === 1) {
                return $programsQuery->first();
            }

            return null;
        }

        $normalizedProgramName = $this->normalizedLookupValue($programName);

        $program = Program::query()
            ->when($departmentId, fn ($query) => $query->where('department_id', $departmentId))
            ->whereRaw('LOWER(TRIM(program_name)) = ?', [$normalizedProgramName])
            ->first();

        if ($program) {
            return $program;
        }

        return Program::query()
            ->when($departmentId, fn ($query) => $query->where('department_id', $departmentId))
            ->whereRaw('LOWER(program_name) LIKE ?', ['%' . $normalizedProgramName . '%'])
            ->first();
    }

    private function resolveDepartmentFromRow(array $row): ?Department
    {
        $departmentId = $this->integerValue($row, 'department_id');
        if ($departmentId) {
            return Department::find($departmentId);
        }

        $departmentName = $this->stringValue($row, 'department_name') ?: $this->stringValue($row, 'department');
        if (! $departmentName) {
            $departmentsQuery = Department::query();

            if ((clone $departmentsQuery)->count() === 1) {
                return $departmentsQuery->first();
            }

            return null;
        }

        $normalizedDepartmentName = $this->normalizedLookupValue($departmentName);

        $department = Department::query()
            ->whereRaw('LOWER(TRIM(department_name)) = ?', [$normalizedDepartmentName])
            ->first();

        if ($department) {
            return $department;
        }

        return Department::query()
            ->whereRaw('LOWER(department_name) LIKE ?', ['%' . $normalizedDepartmentName . '%'])
            ->first();
    }

    private function resolveRoleFromRow(array $row): ?Role
    {
        $roleId = $this->integerValue($row, 'role_id');
        if ($roleId) {
            return Role::find($roleId);
        }

        $roleName = $this->stringValue($row, 'role_name') ?: $this->stringValue($row, 'role');
        if (! $roleName) {
            return null;
        }

        $normalizedRoleName = $this->normalizedLookupValue($roleName);

        $role = Role::query()
            ->whereRaw('LOWER(TRIM(name)) = ?', [$normalizedRoleName])
            ->first();

        if ($role) {
            return $role;
        }

        return Role::query()
            ->whereRaw('LOWER(name) LIKE ?', ['%' . $normalizedRoleName . '%'])
            ->first();
    }

    private function resolveModuleFromRow(array $row, ?int $departmentId = null): ?Module
    {
        $moduleId = $this->integerValue($row, 'module_id');
        if ($moduleId) {
            return Module::query()
                ->when($departmentId, function ($query) use ($departmentId) {
                    $query->whereHas('program', fn ($programQuery) => $programQuery->where('department_id', $departmentId));
                })
                ->find($moduleId);
        }

        $moduleCode = $this->stringValue($row, 'module_code');
        if (! $moduleCode) {
            return null;
        }

        $normalizedModuleCode = $this->normalizedLookupValue($moduleCode);

        $module = Module::query()
            ->when($departmentId, function ($query) use ($departmentId) {
                $query->whereHas('program', fn ($programQuery) => $programQuery->where('department_id', $departmentId));
            })
            ->whereRaw('LOWER(TRIM(module_code)) = ?', [$normalizedModuleCode])
            ->first();

        if ($module) {
            return $module;
        }

        return Module::query()
            ->when($departmentId, function ($query) use ($departmentId) {
                $query->whereHas('program', fn ($programQuery) => $programQuery->where('department_id', $departmentId));
            })
            ->whereRaw('LOWER(module_code) LIKE ?', ['%' . $normalizedModuleCode . '%'])
            ->first();
    }

    private function resolveLecturerFromRow(array $row, ?int $departmentId = null): ?User
    {
        $userId = $this->integerValue($row, 'user_id');
        if ($userId) {
            return User::query()
                ->when($departmentId, fn ($query) => $query->where('department_id', $departmentId))
                ->find($userId);
        }

        $email = $this->stringValue($row, 'lecturer_email') ?: $this->stringValue($row, 'email');
        if (! $email) {
            $lecturerName = $this->stringValue($row, 'lecturer_name');
            if (! $lecturerName) {
                return null;
            }

            $normalizedLecturerName = $this->normalizedLookupValue($lecturerName);

            $user = User::query()
                ->when($departmentId, fn ($query) => $query->where('department_id', $departmentId))
                ->whereRaw('LOWER(TRIM(name)) = ?', [$normalizedLecturerName])
                ->first();

            if ($user) {
                return $user;
            }

            return User::query()
                ->when($departmentId, fn ($query) => $query->where('department_id', $departmentId))
                ->whereRaw('LOWER(name) LIKE ?', ['%' . $normalizedLecturerName . '%'])
                ->first();
        }

        return User::query()
            ->when($departmentId, fn ($query) => $query->where('department_id', $departmentId))
            ->where('email', $email)
            ->first();
    }

    private function resolveStudentFromRow(array $row, ?int $departmentId = null): ?Student
    {
        $studentId = $this->integerValue($row, 'student_id');
        if ($studentId) {
            return Student::query()
                ->when($departmentId, function ($query) use ($departmentId) {
                    $query->whereHas('program', fn ($programQuery) => $programQuery->where('department_id', $departmentId));
                })
                ->find($studentId);
        }

        $adminNumber = $this->stringValue($row, 'student_admin_number') ?: $this->stringValue($row, 'admin_number');
        if (! $adminNumber) {
            return null;
        }

        return Student::query()
            ->when($departmentId, function ($query) use ($departmentId) {
                $query->whereHas('program', fn ($programQuery) => $programQuery->where('department_id', $departmentId));
            })
            ->where('admin_number', $adminNumber)
            ->first();
    }

    private function resolveModuleDistributionFromRow(array $row, ?int $departmentId = null): ?ModuleDistribution
    {
        $distributionId = $this->integerValue($row, 'module_distribution_id');
        if ($distributionId) {
            return ModuleDistribution::query()
                ->whereHas('module.program', fn ($programQuery) => $programQuery->when($departmentId, fn ($departmentQuery) => $departmentQuery->where('department_id', $departmentId)))
                ->find($distributionId);
        }

        $module = $this->resolveModuleFromRow($row, $departmentId);
        $academicYear = $this->stringValue($row, 'academic_year');

        if (! $module || ! $academicYear) {
            return null;
        }

        return ModuleDistribution::query()
            ->where('module_id', $module->id)
            ->where('academic_year', $academicYear)
            ->first();
    }

    private function resolveWeekFromRow(array $row): ?Week
    {
        $weekId = $this->integerValue($row, 'week_id');
        if ($weekId) {
            return Week::find($weekId);
        }

        $weekName = $this->stringValue($row, 'week_name');
        if (! $weekName) {
            return null;
        }

        return Week::where('week_name', $weekName)->first();
    }

    private function resultMessage(string $label, int $created, int $updated, int $skipped, array $errors): array
    {
        $message = sprintf(
            '%s import complete. Created %d, updated %d, skipped %d.',
            $label,
            $created,
            $updated,
            $skipped
        );

        if (count($errors)) {
            $message .= ' Some rows were skipped because of validation issues.';
        }

        return [
            'message' => $message,
            'created' => $created,
            'updated' => $updated,
            'skipped' => $skipped,
            'errors' => $errors,
        ];
    }

    private function redirectFor(string $entity): ?string
    {
        return match ($entity) {
            'departments' => route('departments.index'),
            'weeks' => route('weeks.index'),
            'programs' => route('programs.index'),
            'modules' => route('modules.index'),
            'lecturers' => route('lecturers.index'),
            'hods' => route('hods.index'),
            'students' => route('students.index'),
            'users' => route('users.index'),
            'class_timings' => route('class-timings.index'),
            'roles' => route('roles.index'),
            'attendance_records' => route('attendance.records.index'),
            'module_distributions' => route('moduledistribute.index'),
            default => null,
        };
    }

    private function importRoles(array $rows): array
    {
        abort_unless($this->roleName() === 'hod', 403);

        $created = 0;
        $skipped = 0;
        $errors = [];

        foreach ($rows as $index => $row) {
            $name = $this->stringValue($row, 'name');

            if (! $name) {
                $errors[] = $this->rowError($index, 'name is required.');
                continue;
            }

            $normalizedName = $this->normalizedLookupValue($name);

            $existing = Role::query()
                ->whereRaw('LOWER(TRIM(name)) = ?', [$normalizedName])
                ->first();

            if ($existing) {
                $skipped++;
                continue;
            }

            Role::create(['name' => $name]);
            $created++;
        }

        return $this->resultMessage('Role', $created, 0, $skipped, $errors);
    }

    private function normalizeRow(array $row): array
    {
        return collect($row)
            ->mapWithKeys(function ($value, $key) {
                $normalizedKey = strtolower(trim((string) $key));
                $normalizedKey = preg_replace('/[^a-z0-9]+/', '_', $normalizedKey);
                $normalizedKey = trim((string) $normalizedKey, '_');

                return [$normalizedKey => $this->cleanValue($value)];
            })
            ->all();
    }

    private function cleanValue(mixed $value): mixed
    {
        if (is_string($value)) {
            $trimmed = trim($value);

            return $trimmed === '' ? null : $trimmed;
        }

        return $value;
    }

    private function isEmptyRow(array $row): bool
    {
        foreach ($row as $value) {
            if ($this->cleanValue($value) !== null) {
                return false;
            }
        }

        return true;
    }

    private function stringValue(array $row, string $key): ?string
    {
        $value = $row[$key] ?? null;

        if ($value === null || $value === '') {
            return null;
        }

        return trim((string) $value);
    }

    private function integerValue(array $row, string $key): ?int
    {
        $value = $this->stringValue($row, $key);

        return is_numeric($value) ? (int) $value : null;
    }

    private function normalizedLookupValue(string $value): string
    {
        return strtolower(trim(preg_replace('/\s+/', ' ', $value) ?? $value));
    }

    private function boolValue(array $row, string $primaryKey, ?string $secondaryKey = null): bool
    {
        $value = $row[$primaryKey] ?? null;

        if (($value === null || $value === '') && $secondaryKey) {
            $value = $row[$secondaryKey] ?? null;
        }

        $normalized = strtolower(trim((string) $value));

        return in_array($normalized, ['1', 'true', 'yes', 'present', 'y'], true);
    }

    private function rowError(int $index, string $message): string
    {
        return 'Row ' . ($index + 2) . ': ' . $message;
    }

    private function roleName(): string
    {
        return strtolower(auth()->user()->role->name ?? '');
    }

    private function departmentId(): ?int
    {
        return auth()->user()->hod->department_id ?? auth()->user()->department_id;
    }

    private function roleId(string $name): ?int
    {
        return Role::query()
            ->whereRaw('LOWER(name) = ?', [strtolower($name)])
            ->value('id');
    }
}
