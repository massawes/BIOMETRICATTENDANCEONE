<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PermissionsSeeder extends Seeder
{
    public function run(): void
    {
        // Clear all existing permissions and role_permissions
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        DB::table('role_permissions')->truncate();
        DB::table('permissions')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1');

        $permissions = [
            // ── LECTURER: Attendance Records CRUD ──────────────────────────
            // Lecturer has attendance routes via 'rolelecturer' middleware.
            // These permissions control what they can actually do there.
            [
                'name'             => 'View Attendance Records',
                'key'              => 'view_attendance',
                'group'            => 'Attendance',
                'applicable_roles' => ['lecturer'],
            ],
            [
                'name'             => 'Mark Attendance',
                'key'              => 'create_attendance',
                'group'            => 'Attendance',
                'applicable_roles' => ['lecturer'],
            ],
            [
                'name'             => 'Edit Attendance Record',
                'key'              => 'edit_attendance',
                'group'            => 'Attendance',
                'applicable_roles' => ['lecturer'],
            ],
            [
                'name'             => 'Delete Attendance Record',
                'key'              => 'delete_attendance',
                'group'            => 'Attendance',
                'applicable_roles' => ['lecturer'],
            ],
        ];

        foreach ($permissions as $perm) {
            Permission::create($perm);
        }
    }
}
