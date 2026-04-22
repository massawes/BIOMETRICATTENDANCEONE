<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class StudentsSeeder extends Seeder
{
    public function run(): void
    {
        Schema::disableForeignKeyConstraints();
        DB::table('students')->truncate();
        Schema::enableForeignKeyConstraints();

        // Get all users with role_id = 1 (students)
        $studentUsers = DB::table('users')
            ->where('role_id', 1)
            ->get();

        $intakeYears = ['2023', '2024', '2025', '2026'];
        $records     = [];

        foreach ($studentUsers as $user) {
            $records[] = [
                'student_name' => $user->name,
                'intake'       => $intakeYears[array_rand($intakeYears)],
                'user_id'      => $user->id,
                'program_id'   => $user->program_id,
                'created_at'   => now(),
                'updated_at'   => now(),
            ];
        }

        // Insert in chunks for performance
        foreach (array_chunk($records, 50) as $chunk) {
            DB::table('students')->insert($chunk);
        }

        $this->command->info('✅ Students seeded: ' . count($records) . ' students.');
    }
}
