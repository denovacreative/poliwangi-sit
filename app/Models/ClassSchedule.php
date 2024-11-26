<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClassSchedule extends Model
{
    use HasFactory;

    protected $guarded = [];
    public $keyType = 'string';
    public $incrementing = false;

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function room()
    {
        return $this->belongsTo(Room::class);
    }

    public function meetingType()
    {
        return $this->belongsTo(MeetingType::class);
    }

    public function collegeClass()
    {
        return $this->belongsTo(CollegeClass::class);
    }

    public function presence()
    {
        return $this->hasMany(Presence::class);
    }
}
