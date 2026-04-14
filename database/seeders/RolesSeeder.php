<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class RolesSeeder extends Seeder
{
    public function run(): void
    {
        Schema::disableForeignKeyConstraints();
        DB::table('roles')->truncate();
        Schema::enableForeignKeyConstraints();

        $roles = [
            ['id' => 1, 'name' => 'student',            'created_at' => now(), 'updated_at' => now()],
            ['id' => 2, 'name' => 'lecturer',           'created_at' => now(), 'updated_at' => now()],
            ['id' => 3, 'name' => 'HOD',                'created_at' => now(), 'updated_at' => now()],
            ['id' => 4, 'name' => 'registrar',          'created_at' => now(), 'updated_at' => now()],
            ['id' => 5, 'name' => 'examination_officer','created_at' => now(), 'updated_at' => now()],
            ['id' => 6, 'name' => 'quality_assurance',  'created_at' => now(), 'updated_at' => now()],
            ['id' => 7, 'name' => 'director_academic',  'created_at' => now(), 'updated_at' => now()],
            ['id' => 8, 'name' => 'rector',             'created_at' => now(), 'updated_at' => now()],
        ];

        DB::table('roles')->insert($roles);
        $this->command->info('✅ Roles seeded successfully.');
    }
}
