<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Mtvs\EloquentHashids\HasHashid;
use Mtvs\EloquentHashids\HashidRouting;

class AcademicYear extends Model
{
    use HasFactory, HasHashid, HashidRouting;

    protected $guarded = [];
    protected $appends = ['hashid'];

    public function classGroup()
    {
        return $this->hasMany(ClassGroup::class);
    }

    public function academicPeriod()
    {
        return $this->hasMany(AcademicPeriod::class);
    }
}
