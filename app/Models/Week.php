<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Week extends Model
{
    protected $fillable = ['week_name','allowed'];

    // week ina class nyingi
    public function classTimings()
    {
        return $this->hasMany(ClassTiming::class);
    }

    // week ina attendance nyingi
    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }
}