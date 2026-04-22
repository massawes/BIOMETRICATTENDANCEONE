<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info('🗑️ Truncating all tables before seeding...');

        Schema::disableForeignKeyConstraints();

        // Truncate tables in correct order (kutokana na foreign key dependencies)
        DB::table('attendances')->truncate();
        DB::table('class_timings')->truncate();
        DB::table('module_distributions')->truncate();
        DB::table('students')->truncate();
        DB::table('lecturers')->truncate();
        DB::table('hods')->truncate();
        DB::table('modules')->truncate();
        DB::table('programs')->truncate();
        DB::table('users')->truncate();
        DB::table('departments')->truncate();
        DB::table('roles')->truncate();
        DB::table('weeks')->truncate();           // Ikiwa inatumika
        DB::table('devices')->truncate();         // Optional

        Schema::enableForeignKeyConstraints();

        $this->command->info('✅ All tables truncated successfully.');

        // Sasa anza ku-seed data
        $this->call([
            RolesSeeder::class,
            DepartmentsSeeder::class,
            ProgramsSeeder::class,
            ModulesSeeder::class,
            UsersSeeder::class,
            StudentsSeeder::class,
            LecturersSeeder::class,
            HodsSeeder::class,
            ModuleDistributionSeeder::class,
            ClassTimingSeeder::class,
            WeeksSeeder::class,
            AttendanceSeeder::class,
        ]);

        $this->command->info('🎉 Database seeding completed successfully!');
    }
}
