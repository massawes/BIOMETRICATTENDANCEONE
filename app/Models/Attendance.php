<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    protected $fillable = [
        'module_distribution_id',
        'class_timing_id',
        'week_id',
        'student_id',
        'academic_year',
        'date',
        'is_present',
        'attendance_source',
    ];

    public function classTiming()
    {
        return $this->belongsTo(ClassTiming::class);
    }

    public function week()
    {
        return $this->belongsTo(Week::class);
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function moduleDistribution()
    {
        return $this->belongsTo(ModuleDistribution::class);
    }
}
