<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Hash;

class UsersSeeder extends Seeder
{
    public function run(): void
    {
        Schema::disableForeignKeyConstraints();
        DB::table('students')->truncate();
        DB::table('lecturers')->truncate();
        DB::table('hods')->truncate();
        DB::table('users')->truncate();
        Schema::enableForeignKeyConstraints();

        $users = [];

        // ================================================================
        // SYSTEM ADMIN ROLES (non-department users)
        // ================================================================
        $systemUsers = [
            ['name' => 'Rector',             'email' => 'rector@college.ac.tz',           'role_id' => 8, 'program_id' => null, 'department_id' => null],
            ['name' => 'Director Academic',  'email' => 'director@college.ac.tz',         'role_id' => 7, 'program_id' => null, 'department_id' => null],
            ['name' => 'Quality Assurance',  'email' => 'qa@college.ac.tz',               'role_id' => 6, 'program_id' => null, 'department_id' => null],
            ['name' => 'Examination Officer','email' => 'examofficer@college.ac.tz',      'role_id' => 5, 'program_id' => null, 'department_id' => null],
            ['name' => 'Registrar',          'email' => 'registrar@college.ac.tz',        'role_id' => 4, 'program_id' => null, 'department_id' => null],
        ];

        foreach ($systemUsers as $u) {
            $users[] = [
                'name'          => $u['name'],
                'email'         => $u['email'],
                'password'      => Hash::make('password123'),
                'role_id'       => $u['role_id'],
                'program_id'    => $u['program_id'],
                'department_id' => $u['department_id'],
                'created_at'    => now(),
                'updated_at'    => now(),
            ];
        }

        DB::table('users')->insert($users);

        // ================================================================
        // DEPARTMENT DATA STRUCTURE
        // dept_id => [programs with their module ids, lecturer names]
        // ================================================================
        $departments = [
            1 => [ // ICT
                'programs' => [1, 2, 3, 4, 5],
                'hod'      => ['name' => 'Dr. Amina Juma',      'email' => 'hod.ict@college.ac.tz'],
                'lecturers' => [
                    ['name' => 'Mr. Hassan Omari',    'email' => 'hassan.omari@college.ac.tz'],
                    ['name' => 'Ms. Grace Wambua',    'email' => 'grace.wambua@college.ac.tz'],
                    ['name' => 'Mr. Peter Makundi',   'email' => 'peter.makundi@college.ac.tz'],
                    ['name' => 'Ms. Salma Rashid',    'email' => 'salma.rashid@college.ac.tz'],
                    ['name' => 'Mr. John Kimani',     'email' => 'john.kimani@college.ac.tz'],
                    ['name' => 'Ms. Fatuma Ally',     'email' => 'fatuma.ally@college.ac.tz'],
                    ['name' => 'Mr. David Mwangi',    'email' => 'david.mwangi@college.ac.tz'],
                ],
            ],
            2 => [ // Business Administration
                'programs' => [6, 7, 8, 9, 10],
                'hod'      => ['name' => 'Dr. Rose Mwenda',      'email' => 'hod.ba@college.ac.tz'],
                'lecturers' => [
                    ['name' => 'Mr. Ali Hassan',      'email' => 'ali.hassan@college.ac.tz'],
                    ['name' => 'Ms. Neema Mtui',      'email' => 'neema.mtui@college.ac.tz'],
                    ['name' => 'Mr. James Okonkwo',   'email' => 'james.okonkwo@college.ac.tz'],
                    ['name' => 'Ms. Mary Ndungu',     'email' => 'mary.ndungu@college.ac.tz'],
                    ['name' => 'Mr. Bakari Salim',    'email' => 'bakari.salim@college.ac.tz'],
                    ['name' => 'Ms. Joyce Achieng',   'email' => 'joyce.achieng@college.ac.tz'],
                    ['name' => 'Mr. Richard Kimaro',  'email' => 'richard.kimaro@college.ac.tz'],
                ],
            ],
            3 => [ // Civil Engineering
                'programs' => [11, 12, 13, 14, 15],
                'hod'      => ['name' => 'Eng. Samuel Mwita',    'email' => 'hod.civil@college.ac.tz'],
                'lecturers' => [
                    ['name' => 'Mr. Jabir Mwema',     'email' => 'jabir.mwema@college.ac.tz'],
                    ['name' => 'Ms. Sophia Mchome',   'email' => 'sophia.mchome@college.ac.tz'],
                    ['name' => 'Mr. Patrick Nyerere', 'email' => 'patrick.nyerere@college.ac.tz'],
                    ['name' => 'Ms. Agnes Mmari',     'email' => 'agnes.mmari@college.ac.tz'],
                    ['name' => 'Mr. Charles Mushi',   'email' => 'charles.mushi@college.ac.tz'],
                    ['name' => 'Ms. Dorcas Temba',    'email' => 'dorcas.temba@college.ac.tz'],
                    ['name' => 'Mr. Francis Ngowi',   'email' => 'francis.ngowi@college.ac.tz'],
                ],
            ],
            4 => [ // Electrical Engineering
                'programs' => [16, 17, 18, 19, 20],
                'hod'      => ['name' => 'Eng. Musa Baraka',     'email' => 'hod.elect@college.ac.tz'],
                'lecturers' => [
                    ['name' => 'Mr. Gabriel Lema',    'email' => 'gabriel.lema@college.ac.tz'],
                    ['name' => 'Ms. Mariam Mtanga',   'email' => 'mariam.mtanga@college.ac.tz'],
                    ['name' => 'Mr. Vincent Komba',   'email' => 'vincent.komba@college.ac.tz'],
                    ['name' => 'Ms. Elizabeth Lukas', 'email' => 'elizabeth.lukas@college.ac.tz'],
                    ['name' => 'Mr. Anthony Njau',    'email' => 'anthony.njau@college.ac.tz'],
                    ['name' => 'Ms. Zainab Hamisi',   'email' => 'zainab.hamisi@college.ac.tz'],
                    ['name' => 'Mr. Thomas Mlay',     'email' => 'thomas.mlay@college.ac.tz'],
                ],
            ],
            5 => [ // Mechanical Engineering
                'programs' => [21, 22, 23, 24, 25],
                'hod'      => ['name' => 'Eng. Daniel Msigwa',   'email' => 'hod.mech@college.ac.tz'],
                'lecturers' => [
                    ['name' => 'Mr. Simon Mwakitwange','email' => 'simon.mwakitwange@college.ac.tz'],
                    ['name' => 'Ms. Juliana Mwanga',  'email' => 'juliana.mwanga@college.ac.tz'],
                    ['name' => 'Mr. Robert Kishimba', 'email' => 'robert.kishimba@college.ac.tz'],
                    ['name' => 'Ms. Catherine Mushi', 'email' => 'catherine.mushi@college.ac.tz'],
                    ['name' => 'Mr. Andrew Mganga',   'email' => 'andrew.mganga@college.ac.tz'],
                    ['name' => 'Ms. Irene Mwita',     'email' => 'irene.mwita@college.ac.tz'],
                    ['name' => 'Mr. Oscar Nchimbi',   'email' => 'oscar.nchimbi@college.ac.tz'],
                ],
            ],
            6 => [ // Automotive Engineering
                'programs' => [26, 27, 28, 29, 30],
                'hod'      => ['name' => 'Eng. Joseph Macha',    'email' => 'hod.auto@college.ac.tz'],
                'lecturers' => [
                    ['name' => 'Mr. Emmanuel Mwanga', 'email' => 'emmanuel.mwanga@college.ac.tz'],
                    ['name' => 'Ms. Veronica Mhagama','email' => 'veronica.mhagama@college.ac.tz'],
                    ['name' => 'Mr. Leonard Mkude',   'email' => 'leonard.mkude@college.ac.tz'],
                    ['name' => 'Ms. Ruth Mwakalobo',  'email' => 'ruth.mwakalobo@college.ac.tz'],
                    ['name' => 'Mr. Stephen Ndonde',  'email' => 'stephen.ndonde@college.ac.tz'],
                    ['name' => 'Ms. Agatha Mwamuye',  'email' => 'agatha.mwamuye@college.ac.tz'],
                    ['name' => 'Mr. Michael Mtaani',  'email' => 'michael.mtaani@college.ac.tz'],
                ],
            ],
        ];

        // Student first names and last names for realistic generation
        $firstNames = [
            'Amina','Bakari','Celestina','Daudi','Esther','Fadhili','Grace','Hassan',
            'Irene','Jabir','Kalunde','Latifa','Musa','Neema','Omar','Priscilla',
            'Quentin','Rahel','Salim','Tumaini','Upendo','Vincent','Wanjiku','Xolani',
            'Yusuf','Zawadi','Ahmed','Beatrice','Carlos','Diana','Edwin','Florence',
            'George','Hannah','Ibrahim','Janet','Kevin','Lucy','Martin','Nadia',
            'Oscar','Penina','Rashid','Stella','Teddy','Ummi','Victor','Winnie',
        ];
        $lastNames = [
            'Juma','Omari','Wambua','Makundi','Rashid','Kimani','Ally','Mwangi',
            'Hassan','Mtui','Okonkwo','Ndungu','Salim','Achieng','Kimaro','Mwita',
            'Mwema','Mchome','Nyerere','Mmari','Mushi','Temba','Ngowi','Lema',
            'Mtanga','Komba','Lukas','Njau','Hamisi','Mlay','Mwakitwange','Mwanga',
            'Kishimba','Mganga','Mwita','Nchimbi','Macha','Mkude','Ndonde','Mtaani',
        ];

        // Store user IDs by department for later seeding
        $hodUserIds      = [];  // dept_id => user_id
        $lecturerUserIds = [];  // dept_id => [user_id, ...]
        $studentUserIds  = [];  // dept_id => [['user_id'=>, 'program_id'=>], ...]

        foreach ($departments as $deptId => $deptData) {
            // ---- HOD ----
            $hodUser = [
                'name'          => $deptData['hod']['name'],
                'email'         => $deptData['hod']['email'],
                'password'      => Hash::make('password123'),
                'role_id'       => 3,
                'program_id'    => null,
                'department_id' => $deptId,
                'created_at'    => now(),
                'updated_at'    => now(),
            ];
            DB::table('users')->insert($hodUser);
            $hodUserId = DB::getPdo()->lastInsertId();
            $hodUserIds[$deptId] = $hodUserId;

            // ---- LECTURERS ----
            $lecturerUserIds[$deptId] = [];
            foreach ($deptData['lecturers'] as $lect) {
                $lectUser = [
                    'name'          => $lect['name'],
                    'email'         => $lect['email'],
                    'password'      => Hash::make('password123'),
                    'role_id'       => 2,
                    'program_id'    => null,
                    'department_id' => $deptId,
                    'created_at'    => now(),
                    'updated_at'    => now(),
                ];
                DB::table('users')->insert($lectUser);
                $lecturerUserIds[$deptId][] = DB::getPdo()->lastInsertId();
            }

            // ---- STUDENTS (30 per department, distribute across programs) ----
            $studentUserIds[$deptId] = [];
            $programs = $deptData['programs'];
            $studentsPerProgram = 6; // 5 programs × 6 = 30 students per dept

            foreach ($programs as $progId) {
                for ($s = 1; $s <= $studentsPerProgram; $s++) {
                    $firstName = $firstNames[array_rand($firstNames)];
                    $lastName  = $lastNames[array_rand($lastNames)];
                    $fullName  = $firstName . ' ' . $lastName;
                    $emailSlug = strtolower(str_replace(' ', '.', $fullName));
                    $email     = $emailSlug . '.' . $deptId . $progId . $s . '@student.college.ac.tz';

                    $stuUser = [
                        'name'          => $fullName,
                        'email'         => $email,
                        'password'      => Hash::make('password123'),
                        'role_id'       => 1,
                        'program_id'    => $progId,
                        'department_id' => $deptId,
                        'created_at'    => now(),
                        'updated_at'    => now(),
                    ];
                    DB::table('users')->insert($stuUser);
                    $stuUserId = DB::getPdo()->lastInsertId();
                    $studentUserIds[$deptId][] = [
                        'user_id'    => $stuUserId,
                        'program_id' => $progId,
                        'name'       => $fullName,
                    ];
                }
            }
        }

        // Save to cache for other seeders
        cache(['hodUserIds'      => $hodUserIds]);
        cache(['lecturerUserIds' => $lecturerUserIds]);
        cache(['studentUserIds'  => $studentUserIds]);
        cache(['departments'     => $departments]);

        $this->command->info('✅ Users seeded successfully (HODs, Lecturers, Students for all 6 departments).');
    }
}
