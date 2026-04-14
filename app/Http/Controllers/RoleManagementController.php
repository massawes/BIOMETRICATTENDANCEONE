<?php

namespace App\Http\Controllers;

use App\Models\Role;
use Illuminate\Http\Request;

class RoleManagementController extends Controller
{
    public function index(Request $request)
    {
        $roles = Role::when($request->filled('search'), function ($query) use ($request) {
            $query->where('name', 'like', '%' . $request->search . '%');
        })->paginate(10);

        return view('management.roles.index', compact('roles'));
    }

    public function export(Request $request)
    {
        $roles = Role::when($request->filled('search'), function ($query) use ($request) {
            $query->where('name', 'like', '%' . $request->search . '%');
        })
            ->orderBy('name')
            ->get();

        return response()->json([
            'sheet_name' => 'Roles',
            'filename' => 'roles-export.xlsx',
            'rows' => $roles->map(fn ($role) => [
                'name' => $role->name,
            ])->values(),
        ]);
    }

    public function create()
    {
        return view('management.roles.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:roles,name',
        ]);

        Role::create($validated);

        return redirect()->route('roles.index')->with('success', 'Role created successfully.');
    }

    public function edit(Role $role)
    {
        return view('management.roles.edit', compact('role'));
    }

    public function update(Request $request, Role $role)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:roles,name,' . $role->id,
        ]);

        $role->update($validated);

        return redirect()->route('roles.index')->with('success', 'Role updated successfully.');
    }

    public function destroy(Role $role)
    {
        $role->delete();

        return redirect()->route('roles.index')->with('success', 'Role deleted successfully.');
    }
}
