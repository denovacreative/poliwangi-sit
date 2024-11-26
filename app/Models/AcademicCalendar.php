<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Mtvs\EloquentHashids\HasHashid;
use Mtvs\EloquentHashids\HashidRouting;

class AcademicCalendar extends Model
{
    use HasFactory, HasHashid, HashidRouting;

    protected $fillable = ['name', 'date_start', 'date_end', 'is_national_holiday', 'is_academic_holiday', 'academic_period_id', 'academic_activity_id'];
    protected $hidden = ['id'];
    protected $appends = ['hashid'];

    public function academicPeriod()
    {
        return $this->belongsTo(AcademicPeriod::class);
    }

    public function academicActivity()
    {
        return $this->belongsTo(AcademicActivity::class);
    }
}
