<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WeeklySchedule extends Model
{
    use HasFactory;
    protected $guarded = [];
    public $keyType = 'string';


    public function teachingLecturer()
    {
        return $this->hasMany(TeachingLecturer::class);
    }
    public function day()
    {
        return $this->belongsTo(Day::class);
    }

    public function collegeClass()
    {
        return $this->belongsTo(CollegeClass::class);
    }

    public function room()
    {
        return $this->belongsTo(Room::class);
    }

    public function meetingType()
    {
        return $this->belongsTo(MeetingType::class);
    }
}
