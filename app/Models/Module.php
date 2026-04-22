<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Module extends Model
{
    protected $guarded = [];

    public function program()
    {
        return $this->belongsTo(Program::class);
    }

    public function distributions()
    {
        return $this->hasMany(ModuleDistribution::class);
    }
}