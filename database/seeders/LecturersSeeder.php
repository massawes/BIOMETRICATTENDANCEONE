<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class LecturersSeeder extends Seeder
{
    public function run(): void
    {
        Schema::disableForeignKeyConstraints();
        DB::table('lecturers')->truncate();
        Schema::enableForeignKeyConstraints();

        // Get all users with role_id = 2 (lecturers)
        $lecturerUsers = DB::table('users')
            ->where('role_id', 2)
            ->get();

        $records = [];
        foreach ($lecturerUsers as $user) {
            $records[] = [
                'user_id'       => $user->id,
                'lecturer_name' => $user->name,
                'department_id' => $user->department_id,
                'created_at'    => now(),
                'updated_at'    => now(),
            ];
        }

        foreach (array_chunk($records, 50) as $chunk) {
            DB::table('lecturers')->insert($chunk);
        }

        $this->command->info('✅ Lecturers seeded: ' . count($records) . ' lecturers.');
    }
}
