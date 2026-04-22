<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class PruneZkbioRawLogs extends Command
{
    protected $signature = 'zkbio:prune-raw-logs
        {--days=90 : Keep raw ZKBio scans from this many recent days}
        {--limit=5000 : Maximum raw scan rows to delete per run}
        {--include-errors : Also delete raw scans whose sync status is error}
        {--dry-run : Show how many rows would be deleted without deleting}';

    protected $description = 'Delete old raw ZKBio iclock_transaction rows after they have been processed into attendance sync history.';

    public function handle(): int
    {
        if (! Schema::hasTable('iclock_transaction')) {
            $this->warn('Table iclock_transaction was not found.');

            return self::SUCCESS;
        }

        if (! Schema::hasTable('zkbio_attendance_syncs')) {
            $this->warn('Table zkbio_attendance_syncs was not found.');

            return self::SUCCESS;
        }

        $days = max(1, (int) $this->option('days'));
        $limit = max(1, (int) $this->option('limit'));
        $cutoff = Carbon::now()->subDays($days)->toDateTimeString();
        $statuses = ['synced', 'skipped'];

        if ($this->option('include-errors')) {
            $statuses[] = 'error';
        }

        $transactionIds = DB::table('zkbio_attendance_syncs')
            ->whereIn('status', $statuses)
            ->where('punch_time', '<', $cutoff)
            ->orderBy('zkbio_transaction_id')
            ->limit($limit)
            ->pluck('zkbio_transaction_id');

        if ($transactionIds->isEmpty()) {
            $this->info("No processed raw ZKBio scans older than {$days} day(s) were found.");

            return self::SUCCESS;
        }

        $query = DB::table('iclock_transaction')
            ->whereIn('id', $transactionIds);

        $count = (clone $query)->count();

        if ($this->option('dry-run')) {
            $this->info("Dry run: {$count} raw ZKBio scan row(s) would be deleted. Cutoff: {$cutoff}.");

            return self::SUCCESS;
        }

        $deleted = $query->delete();

        $this->info("Deleted {$deleted} old raw ZKBio scan row(s). Cutoff: {$cutoff}.");
        $this->line('Attendance records and sync history were kept.');

        return self::SUCCESS;
    }
}
