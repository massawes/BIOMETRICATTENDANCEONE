<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Program;
use Illuminate\Http\Request;

class ProgramManagementController extends Controller
{
    public function index(Request $request)
    {
        $programs = Program::with('department')
            ->where('department_id', $this->hodDepartmentId())
            ->when($request->filled('search'), function ($query) use ($request) {
                $query->where('program_name', 'like', '%' . $request->search . '%');
            })
            ->paginate(10);

        return view('management.programs.index', compact('programs'));
    }

    public function export(Request $request)
    {
        $programs = Program::with('department')
            ->where('department_id', $this->hodDepartmentId())
            ->when($request->filled('search'), function ($query) use ($request) {
                $query->where('program_name', 'like', '%' . $request->search . '%');
            })
            ->orderBy('program_name')
            ->get();

        return response()->json([
            'sheet_name' => 'Programs',
            'filename' => 'programs-export.xlsx',
            'rows' => $programs->map(fn ($program) => [
                'program_name' => $program->program_name,
                'department_name' => $program->department?->department_name,
            ])->values(),
        ]);
    }

    public function create()
    {
        $department = Department::findOrFail($this->hodDepartmentId());

        return view('management.programs.create', compact('department'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'program_name' => 'required|string|max:255',
        ]);

        $validated['department_id'] = $this->hodDepartmentId();

        Program::create($validated);

        return redirect()->route('programs.index')->with('success', 'Program created successfully.');
    }

    public function edit(Program $program)
    {
        abort_unless($program->department_id === $this->hodDepartmentId(), 403);

        $department = Department::findOrFail($this->hodDepartmentId());

        return view('management.programs.edit', compact('program', 'department'));
    }

    public function update(Request $request, Program $program)
    {
        abort_unless($program->department_id === $this->hodDepartmentId(), 403);

        $validated = $request->validate([
            'program_name' => 'required|string|max:255',
        ]);

        $program->update($validated);

        return redirect()->route('programs.index')->with('success', 'Program updated successfully.');
    }

    public function destroy(Program $program)
    {
        abort_unless($program->department_id === $this->hodDepartmentId(), 403);

        $program->delete();

        return redirect()->route('programs.index')->with('success', 'Program deleted successfully.');
    }

    private function hodDepartmentId(): ?int
    {
        return auth()->user()->hod->department_id ?? auth()->user()->department_id;
    }
}
