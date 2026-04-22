<?php

namespace App\Console\Commands;

use App\Models\Student;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class ImportZkbioStudents extends Command
{
    protected $signature = 'zkbio:import-students
        {--program-id=1 : Program ID to assign to imported ZKBio people}
        {--intake= : Intake year to assign}
        {--limit=500 : Maximum number of ZKBio people to process}
        {--dry-run : Show what would happen without writing anything}';

    protected $description = 'Import ZKBio personnel as Laravel students and map emp_code to fingerprint_id.';

    public function handle(): int
    {
        $programId = (int) $this->option('program-id');
        $intake = $this->option('intake') ?: now()->year;
        $limit = max(1, (int) $this->option('limit'));
        $studentRoleId = (int) DB::table('roles')->where('name', 'student')->value('id');

        if (! DB::table('programs')->where('id', $programId)->exists()) {
            $this->error("Program ID {$programId} does not exist.");

            return self::FAILURE;
        }

        if (! $studentRoleId) {
            $this->error('Student role was not found.');

            return self::FAILURE;
        }

        $people = DB::table('personnel_employee')
            ->select(['emp_code', 'first_name', 'last_name'])
            ->whereNotNull('emp_code')
            ->orderBy('id')
            ->limit($limit)
            ->get()
            ->filter(fn ($person) => ctype_digit((string) $person->emp_code) && $this->fullName($person) !== '');

        $summary = [
            'created' => 0,
            'updated' => 0,
            'skipped' => 0,
        ];

        foreach ($people as $person) {
            $fingerprintId = (int) $person->emp_code;
            $name = $this->fullName($person);

            $student = Student::where('fingerprint_id', $fingerprintId)->first();

            if ($student) {
                $summary['skipped']++;
                $this->line("{$fingerprintId}: already mapped to {$student->student_name}.");
                continue;
            }

            $student = Student::whereRaw('LOWER(student_name) = ?', [Str::lower($name)])->first();

            if ($this->option('dry-run')) {
                $action = $student ? 'would update' : 'would create';
                $this->line("{$fingerprintId}: {$action} {$name}.");
                $summary[$student ? 'updated' : 'created']++;
                continue;
            }

            if ($student) {
                $student->update([
                    'fingerprint_id' => $fingerprintId,
                ]);
                $summary['updated']++;
                $this->info("{$fingerprintId}: updated {$name}.");
                continue;
            }

            $user = User::create([
                'name' => $name,
                'email' => 'zkbio-' . $fingerprintId . '@local.test',
                'password' => Hash::make('password'),
                'role_id' => $studentRoleId,
                'program_id' => $programId,
            ]);

            Student::create([
                'student_name' => $name,
                'admin_number' => 'ZKBIO-' . $fingerprintId,
                'intake' => $intake,
                'user_id' => $user->id,
                'program_id' => $programId,
                'fingerprint_id' => $fingerprintId,
            ]);

            $summary['created']++;
            $this->info("{$fingerprintId}: created {$name}.");
        }

        $this->info("Done. Created: {$summary['created']}, updated: {$summary['updated']}, skipped: {$summary['skipped']}.");

        return self::SUCCESS;
    }

    private function fullName(object $person): string
    {
        return trim(preg_replace('/\s+/', ' ', trim(($person->first_name ?? '') . ' ' . ($person->last_name ?? ''))));
    }
}
