<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Mtvs\EloquentHashids\HasHashid;
use Mtvs\EloquentHashids\HashidRouting;

class MeetingType extends Model
{
    use HasFactory, HasHashid, HashidRouting;

    // protected $hidden = ['id'];
    protected $appends = ['hashid'];
    protected $fillable = ['code', 'name', 'alias', 'type', 'is_presence', 'is_exam'];

    public function weeklySchedule()
    {
        return $this->belongsTo(WeeklySchedule::class);
    }
    public function examSchedule()
    {
        return $this->hasMany(ExamSchedule::class);
    }
}
