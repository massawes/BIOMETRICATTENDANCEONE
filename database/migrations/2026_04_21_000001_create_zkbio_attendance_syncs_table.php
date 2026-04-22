<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('zkbio_attendance_syncs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('zkbio_transaction_id')->unique();
            $table->string('emp_code', 20);
            $table->timestamp('punch_time');
            $table->string('terminal_sn', 50)->nullable();
            $table->integer('verify_type')->nullable();
            $table->string('punch_state', 5)->nullable();
            $table->string('status', 20)->default('synced');
            $table->string('message')->nullable();
            $table->foreignId('attendance_id')->nullable()->constrained('attendances')->nullOnDelete();
            $table->timestamps();

            $table->index(['emp_code', 'punch_time']);
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('zkbio_attendance_syncs');
    }
};
