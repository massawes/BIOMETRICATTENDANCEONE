<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BiometricAttendanceSession extends Model
{
    protected $fillable = [
        'lecturer_id',
        'week_id',
        'course_id',
        'module_distribution_id',
        'class_timing_id',
        'day',
        'subject',
        'started_at',
        'ended_at',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'started_at' => 'datetime',
            'ended_at' => 'datetime',
            'is_active' => 'boolean',
        ];
    }
}
