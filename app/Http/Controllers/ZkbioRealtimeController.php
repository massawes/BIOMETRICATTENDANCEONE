<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

class ZkbioRealtimeController extends Controller
{
    public function sync(): JsonResponse
    {
        $before = (int) DB::table('zkbio_attendance_syncs')->max('zkbio_transaction_id');

        Artisan::call('zkbio:sync-attendance', [
            '--limit' => 100,
        ]);

        $after = (int) DB::table('zkbio_attendance_syncs')->max('zkbio_transaction_id');

        return response()->json([
            'ok' => true,
            'changed' => $after > $before,
            'latest_transaction_id' => $after,
        ]);
    }
}
