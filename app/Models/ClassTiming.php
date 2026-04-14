<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClassTiming extends Model
{
    //
    protected $fillable = [
    'day',
    'time',
    'subject',
    'room',
    'module_distribution_id',
    'week_id'
    ];

    public function moduleDistribution()
    {
        return $this->belongsTo(ModuleDistribution::class);
    }

    // belongs to week
    public function week()
    {
        return $this->belongsTo(Week::class);
    }

    // ina attendance nyingi
    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }

}
