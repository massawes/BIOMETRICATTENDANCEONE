<?php

namespace App\Http\Controllers;

use App\Models\Department;
use Illuminate\Http\Request;

class DepartmentController extends Controller
{
    public function index(Request $request)
    {
        $departments = Department::when($request->filled('search'), function ($query) use ($request) {
            $query->where('department_name', 'like', '%' . $request->search . '%');
        })->paginate(10);

        return view('management.departments.index', compact('departments'));
    }

    public function export(Request $request)
    {
        $departments = Department::when($request->filled('search'), function ($query) use ($request) {
            $query->where('department_name', 'like', '%' . $request->search . '%');
        })
            ->orderBy('department_name')
            ->get();

        return response()->json([
            'sheet_name' => 'Departments',
            'filename' => 'departments-export.xlsx',
            'rows' => $departments->map(fn ($department) => [
                'department_name' => $department->department_name,
            ])->values(),
        ]);
    }

    public function create()
    {
        return view('management.departments.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'department_name' => 'required|string|max:255|unique:departments,department_name',
        ]);

        Department::create($validated);

        return redirect()->route('departments.index')->with('success', 'Department created successfully.');
    }

    public function edit(Department $department)
    {
        return view('management.departments.edit', compact('department'));
    }

    public function update(Request $request, Department $department)
    {
        $validated = $request->validate([
            'department_name' => 'required|string|max:255|unique:departments,department_name,' . $department->id,
        ]);

        $department->update($validated);

        return redirect()->route('departments.index')->with('success', 'Department updated successfully.');
    }

    public function destroy(Department $department)
    {
        $department->delete();

        return redirect()->route('departments.index')->with('success', 'Department deleted successfully.');
    }
}
