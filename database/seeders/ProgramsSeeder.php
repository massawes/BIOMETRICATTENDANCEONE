<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ProgramsSeeder extends Seeder
{
    public function run(): void
    {
        Schema::disableForeignKeyConstraints();
        DB::table('programs')->truncate();
        Schema::enableForeignKeyConstraints();

        $programs = [
            // ICT - Department 1
            ['id' => 1,  'program_name' => 'Multimedia Technology',           'department_id' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 2,  'program_name' => 'Cyber Security',                  'department_id' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 3,  'program_name' => 'Information Technology',          'department_id' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 4,  'program_name' => 'Computer Science',                'department_id' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 5,  'program_name' => 'Software Engineering',            'department_id' => 1, 'created_at' => now(), 'updated_at' => now()],

            // Business Administration - Department 2
            ['id' => 6,  'program_name' => 'Diploma in Business Management',  'department_id' => 2, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 7,  'program_name' => 'Accounting and Finance',          'department_id' => 2, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 8,  'program_name' => 'Procurement and Supply Chain',    'department_id' => 2, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 9,  'program_name' => 'Human Resource Management',       'department_id' => 2, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 10, 'program_name' => 'Marketing Management',            'department_id' => 2, 'created_at' => now(), 'updated_at' => now()],

            // Civil Engineering - Department 3
            ['id' => 11, 'program_name' => 'Civil Engineering Technology',    'department_id' => 3, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 12, 'program_name' => 'Construction Management',         'department_id' => 3, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 13, 'program_name' => 'Building Technology',             'department_id' => 3, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 14, 'program_name' => 'Structural Engineering',          'department_id' => 3, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 15, 'program_name' => 'Land Surveying',                  'department_id' => 3, 'created_at' => now(), 'updated_at' => now()],

            // Electrical Engineering - Department 4
            ['id' => 16, 'program_name' => 'Electrical Engineering Technology','department_id' => 4, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 17, 'program_name' => 'Electronics and Telecommunication','department_id' => 4, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 18, 'program_name' => 'Power Engineering',               'department_id' => 4, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 19, 'program_name' => 'Instrumentation and Control',     'department_id' => 4, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 20, 'program_name' => 'Renewable Energy Technology',     'department_id' => 4, 'created_at' => now(), 'updated_at' => now()],

            // Mechanical Engineering - Department 5
            ['id' => 21, 'program_name' => 'Mechanical Engineering Technology','department_id' => 5, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 22, 'program_name' => 'Industrial Maintenance',          'department_id' => 5, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 23, 'program_name' => 'Production Engineering',          'department_id' => 5, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 24, 'program_name' => 'Welding and Fabrication',         'department_id' => 5, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 25, 'program_name' => 'HVAC Technology',                 'department_id' => 5, 'created_at' => now(), 'updated_at' => now()],

            // Automotive Engineering - Department 6
            ['id' => 26, 'program_name' => 'Automotive Engineering Technology','department_id' => 6, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 27, 'program_name' => 'Automotive Maintenance',          'department_id' => 6, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 28, 'program_name' => 'Vehicle Body Repair',             'department_id' => 6, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 29, 'program_name' => 'Diesel Plant Mechanics',          'department_id' => 6, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 30, 'program_name' => 'Hybrid and Electric Vehicles',    'department_id' => 6, 'created_at' => now(), 'updated_at' => now()],
        ];

        DB::table('programs')->insert($programs);
        $this->command->info('✅ Programs seeded: ' . count($programs) . ' programs.');
    }
}
