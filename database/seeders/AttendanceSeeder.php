<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Carbon\Carbon;

class AttendanceSeeder extends Seeder
{
    public function run(): void
    {
        Schema::disableForeignKeyConstraints();
        DB::table('attendances')->truncate();
        Schema::enableForeignKeyConstraints();

        $this->command->info('✅ Attendance table cleared. Manual attendance starts with no prefilled records.');
    }
}
