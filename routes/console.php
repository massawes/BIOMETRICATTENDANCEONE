<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Schedule::command('zkbio:sync-attendance --limit=500')->everyMinute();
Schedule::command('zkbio:prune-raw-logs --days=90 --limit=5000')->dailyAt('02:30');
