<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Department extends Model
{
    protected $fillable = [
        'department_name',
    ];

    public function programs()
    {
        return $this->hasMany(Program::class);
    }

    public function lecturers()
    {
        return $this->hasMany(Lecturer::class);
    }

    public function hods()
    {
        return $this->hasMany(Hod::class);
    }
}
