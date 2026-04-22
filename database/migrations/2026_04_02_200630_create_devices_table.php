<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
       Schema::create('devices', function (Blueprint $table) {
    $table->id();
    $table->string('device_name');
    $table->string('device_dep');
    $table->string('device_uid')->unique();
    $table->date('device_date');
    $table->boolean('device_mode')->default(1); // 0=Enrollment, 1=Attendance
    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('devices');
    }
};
