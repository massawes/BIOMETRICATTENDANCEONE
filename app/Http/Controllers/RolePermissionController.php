<?php

namespace App\Http\Controllers;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Http\Request;

class RolePermissionController extends Controller
{
    public function index(Request $request)
    {
        $roles = Role::orderBy('name')->get();
        $selectedRole    = null;
        $groupedPermissions = collect();
        $assignedKeys    = collect();

        if ($request->filled('role_id')) {
            $selectedRole = Role::with('permissions')->find($request->role_id);

            if ($selectedRole) {
                $roleName     = strtolower($selectedRole->name);
                $assignedKeys = $selectedRole->permissions->pluck('key');

                // Only show permissions whose applicable_roles includes this role
                $groupedPermissions = Permission::all()
                    ->filter(fn($p) => in_array($roleName, $p->applicable_roles ?? []))
                    ->groupBy('group');
            }
        }

        return view('management.role-permissions.index', compact(
            'roles',
            'selectedRole',
            'groupedPermissions',
            'assignedKeys'
        ));
    }

    public function update(Request $request, Role $role)
    {
        $permissionIds = Permission::whereIn('key', $request->input('permissions', []))->pluck('id');
        $role->permissions()->sync($permissionIds);

        return redirect()
            ->route('role-permissions.index', ['role_id' => $role->id])
            ->with('success', 'Permissions updated for ' . $role->name . '.');
    }
}
