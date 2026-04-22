<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class HodsSeeder extends Seeder
{
    public function run(): void
    {
        Schema::disableForeignKeyConstraints();
        DB::table('hods')->truncate();
        Schema::enableForeignKeyConstraints();

        // Get all users with role_id = 3 (HODs)
        $hodUsers = DB::table('users')
            ->where('role_id', 3)
            ->get();

        $records = [];
        foreach ($hodUsers as $user) {
            $records[] = [
                'user_id'       => $user->id,
                'hod_name'      => $user->name,
                'department_id' => $user->department_id,
                'created_at'    => now(),
                'updated_at'    => now(),
            ];
        }

        DB::table('hods')->insert($records);
        $this->command->info('✅ HODs seeded: ' . count($records) . ' HODs.');
    }
}
