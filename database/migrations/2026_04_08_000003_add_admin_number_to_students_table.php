<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('students', function (Blueprint $table) {
            if (! Schema::hasColumn('students', 'admin_number')) {
                $table->string('admin_number')->nullable()->unique()->after('student_name');
            }
        });
    }

    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {
            if (Schema::hasColumn('students', 'admin_number')) {
                $table->dropUnique(['admin_number']);
                $table->dropColumn('admin_number');
            }
        });
    }
};
