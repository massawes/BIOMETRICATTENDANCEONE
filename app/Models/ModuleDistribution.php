<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ModuleDistribution extends Model
{
    protected $guarded = [];

    public function lecturer()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function module()
    {
        return $this->belongsTo(Module::class);
    }

    public function classTimings()
    {
    return $this->hasMany(ClassTiming::class);
    }

    
    
}
