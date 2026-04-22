<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ClassTimingSeeder extends Seeder
{
    public function run(): void
    {
        Schema::disableForeignKeyConstraints();
        DB::table('class_timings')->truncate();
        Schema::enableForeignKeyConstraints();

        // Define possible time slots (realistic college schedule)
        $timeSlots = [
            ['time' => '07:00 - 09:00', 'label' => 'morning_1'],
            ['time' => '09:00 - 11:00', 'label' => 'morning_2'],
            ['time' => '11:00 - 13:00', 'label' => 'morning_3'],
            ['time' => '14:00 - 16:00', 'label' => 'afternoon_1'],
            ['time' => '16:00 - 18:00', 'label' => 'afternoon_2'],
        ];

        $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'];

        // Room assignments by department
        $roomsByDept = [
            1 => ['ICT-Lab-1', 'ICT-Lab-2', 'ICT-Room-A', 'ICT-Room-B', 'ICT-Room-C'],
            2 => ['BA-Room-1', 'BA-Room-2', 'BA-Room-3', 'BA-Hall-A', 'BA-Hall-B'],
            3 => ['Civil-Lab-1', 'Civil-Room-A', 'Civil-Room-B', 'Drawing-Room-1', 'Drawing-Room-2'],
            4 => ['EE-Lab-1', 'EE-Lab-2', 'EE-Room-A', 'EE-Room-B', 'Power-Lab'],
            5 => ['Mech-Lab-1', 'Mech-Lab-2', 'Workshop-1', 'Workshop-2', 'ME-Room-A'],
            6 => ['Auto-Lab-1', 'Auto-Lab-2', 'Auto-Workshop', 'AE-Room-A', 'AE-Room-B'],
        ];

        // Get all module distributions
        $distributions = DB::table('module_distributions')
            ->join('users', 'module_distributions.user_id', '=', 'users.id')
            ->select('module_distributions.id as dist_id', 'users.department_id')
            ->get();

        $records = [];
        // Track used slots per distribution to avoid same day/time double booking
        $usedSlots = [];

        foreach ($distributions as $dist) {
            $deptId = $dist->department_id;
            $distId = $dist->dist_id;
            $rooms  = $roomsByDept[$deptId] ?? ['Room-A', 'Room-B'];

            // Each module needs exactly 2 class sessions per week
            // Pick 2 different days
            $shuffledDays = $days;
            shuffle($shuffledDays);
            $selectedDays = array_slice($shuffledDays, 0, 2);

            foreach ($selectedDays as $day) {
                // Pick a time slot
                $shuffledSlots = $timeSlots;
                shuffle($shuffledSlots);
                $selectedSlot = $shuffledSlots[0];

                // Pick a room
                $room = $rooms[array_rand($rooms)];

                $records[] = [
                    'day'                    => $day,
                    'time'                   => $selectedSlot['time'],
                    'room'                   => $room,
                    'module_distribution_id' => $distId,
                    'created_at'             => now(),
                    'updated_at'             => now(),
                ];
            }
        }

        foreach (array_chunk($records, 200) as $chunk) {
            DB::table('class_timings')->insert($chunk);
        }

        $this->command->info('✅ Class timings seeded: ' . count($records) . ' records (2 per module distribution).');
    }
}
