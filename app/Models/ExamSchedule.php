<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExamSchedule extends Model
{
    use HasFactory;

    protected $guarded = [];

    public $incrementing = false;
    public $keyType = "string";

    public function collegeClass()
    {
        return $this->belongsTo(CollegeClass::class);
    }

    public function meetingType()
    {
        return $this->belongsTo(MeetingType::class);
    }

    public function room()
    {
        return $this->belongsTo(Room::class);
    }

    public function employee1()
    {
        return $this->belongsTo(Employee::class, 'employee_id_1', 'id');
    }

    public function employee2()
    {
        return $this->belongsTo(Employee::class, 'employee_id_2', 'id');
    }
}
