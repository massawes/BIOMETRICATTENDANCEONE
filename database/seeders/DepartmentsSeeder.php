<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class DepartmentsSeeder extends Seeder
{
    public function run(): void
    {
        Schema::disableForeignKeyConstraints();
        DB::table('departments')->truncate();
        Schema::enableForeignKeyConstraints();

        $departments = [
            ['id' => 1, 'department_name' => 'Information Communication Technology', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 2, 'department_name' => 'Business Administration',               'created_at' => now(), 'updated_at' => now()],
            ['id' => 3, 'department_name' => 'Civil Engineering',                     'created_at' => now(), 'updated_at' => now()],
            ['id' => 4, 'department_name' => 'Electrical Engineering',                'created_at' => now(), 'updated_at' => now()],
            ['id' => 5, 'department_name' => 'Mechanical Engineering',                'created_at' => now(), 'updated_at' => now()],
            ['id' => 6, 'department_name' => 'Automotive Engineering',                'created_at' => now(), 'updated_at' => now()],
        ];

        DB::table('departments')->insert($departments);
        $this->command->info('✅ Departments seeded successfully.');
    }
}
