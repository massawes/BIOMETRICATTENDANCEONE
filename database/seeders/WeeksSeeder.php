<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class WeeksSeeder extends Seeder
{
    public function run(): void
    {
        Schema::disableForeignKeyConstraints();
        DB::table('weeks')->truncate();
        Schema::enableForeignKeyConstraints();

        $records = [];

        for ($week = 1; $week <= 15; $week++) {
            $records[] = [
                'week_name'  => 'Week ' . $week,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        DB::table('weeks')->insert($records);

        $this->command->info('✅ Weeks seeded: ' . count($records) . ' weeks.');
    }
}
