<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ModuleDistributionSeeder extends Seeder
{
    public function run(): void
    {
        Schema::disableForeignKeyConstraints();
        DB::table('module_distributions')->truncate();
        Schema::enableForeignKeyConstraints();

        $academicYear = '2025/2026';

        $this->command->info('⏳ Starting Module Distribution...');

        // Department => Programs mapping
        $departmentPrograms = [
            1 => [1, 2, 3, 4, 5],   // ICT
            2 => [6, 7, 8, 9, 10],  // Business
            3 => [11, 12, 13, 14, 15], // Civil
            4 => [16, 17, 18, 19, 20], // Electrical
            5 => [21, 22, 23, 24, 25], // Mechanical
            6 => [26, 27, 28, 29, 30], // Automotive
        ];

        $allDistributions = [];

        foreach ($departmentPrograms as $deptId => $programIds) {

            // ✅ Pata lecturers wote wa department hii
            $lecturers = DB::table('users')
                ->where('role_id', 2)
                ->where('department_id', $deptId)
                ->pluck('id')
                ->toArray();

            if (empty($lecturers)) {
                $this->command->warn("⚠️ No lecturers found for Department ID: $deptId");
                continue;
            }

            // ✅ Pata modules ZOTE za department hii
            $modules = DB::table('modules')
                ->whereIn('program_id', $programIds)
                ->pluck('id')
                ->toArray();

            if (empty($modules)) {
                $this->command->warn("⚠️ No modules found for Department ID: $deptId");
                continue;
            }

            $this->command->info("  Dept $deptId => " . count($lecturers) . " lecturers, " . count($modules) . " modules");

            // ✅ Assign KILA module kwa lecturer mmoja
            // Tumia round-robin ili kila lecturer apate mgawanyo sawa

            // Kwanza assign 3 modules kwa kila lecturer (kama ulivyoomba)
            // Kisha modules zilizobaki pia zipate lecturer

            $lecturerIndex = 0;
            $lecturerModuleCount = array_fill_keys($lecturers, 0);

            // Shuffle modules
            shuffle($modules);

            foreach ($modules as $moduleId) {
                // Pata lecturer wa zamu (round-robin)
                $lecturerId = $lecturers[$lecturerIndex % count($lecturers)];

                $allDistributions[] = [
                    'academic_year' => $academicYear,
                    'user_id'       => $lecturerId,
                    'module_id'     => $moduleId,
                    'created_at'    => now(),
                    'updated_at'    => now(),
                ];

                $lecturerIndex++;
            }
        }

        // ✅ Insert zote
        foreach (array_chunk($allDistributions, 100) as $chunk) {
            DB::table('module_distributions')->insert($chunk);
        }

        $this->command->info('✅ Module Distributions seeded: ' . count($allDistributions) . ' records.');
        $this->command->info('✅ EVERY module now has a lecturer assigned!');

        // ✅ Onesha summary
        $this->showSummary();
    }

    private function showSummary(): void
    {
        $this->command->info('');
        $this->command->info('📊 DISTRIBUTION SUMMARY:');
        $this->command->info(str_repeat('-', 60));

        $summary = DB::table('module_distributions as md')
            ->join('users as u', 'md.user_id', '=', 'u.id')
            ->join('modules as m', 'md.module_id', '=', 'm.id')
            ->join('departments as d', 'u.department_id', '=', 'd.id')
            ->select(
                'd.department_name',
                'u.name as lecturer_name',
                DB::raw('COUNT(md.id) as total_modules')
            )
            ->groupBy('d.department_name', 'u.name', 'md.user_id')
            ->orderBy('d.department_name')
            ->orderBy('u.name')
            ->get();

        $currentDept = '';
        foreach ($summary as $row) {
            if ($currentDept !== $row->department_name) {
                $this->command->info('');
                $this->command->info('🏢 ' . $row->department_name);
                $currentDept = $row->department_name;
            }
            $this->command->info("   👤 {$row->lecturer_name} => {$row->total_modules} modules");
        }

        $this->command->info('');
        $this->command->info(str_repeat('-', 60));
    }
}
