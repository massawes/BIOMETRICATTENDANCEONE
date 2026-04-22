<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('attendances', function (Blueprint $table) {
            if (! Schema::hasColumn('attendances', 'attendance_source')) {
                $table->string('attendance_source', 20)->default('manual')->after('is_present');
            }
        });

        DB::table('attendances')
            ->whereNull('attendance_source')
            ->update(['attendance_source' => 'manual']);
    }

    public function down(): void
    {
        Schema::table('attendances', function (Blueprint $table) {
            if (Schema::hasColumn('attendances', 'attendance_source')) {
                $table->dropColumn('attendance_source');
            }
        });
    }
};
