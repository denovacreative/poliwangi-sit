<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CollegeClass extends Model
{
    use HasFactory;

    protected $guarded = [];
    public $keyType = "string";

    // protected static function boot()
    // {
    //     parent::boot();

    //     static::creating(function ($model) {
    //         try {
    //             $model->id = (string) Str::uuid();
    //         } catch (Exception $e) {
    //             abort(500, $e->getMessage());
    //         }
    //     });
    // }

    public function academicPeriod()
    {
        return $this->belongsTo(AcademicPeriod::class);
    }

    public function studyProgram()
    {
        return $this->belongsTo(StudyProgram::class);
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function classParticipant()
    {
        return $this->hasMany(ClassParticipant::class);
    }

    public function lectureSystem()
    {
        return $this->belongsTo(LectureSystem::class);
    }

    public function teachingLecturer()
    {
        return $this->hasMany(TeachingLecturer::class);
    }

    public function classSchedule()
    {
        return $this->hasMany(ClassSchedule::class);
    }

    public function presence()
    {
        return $this->hasMany(Presence::class);
    }

    public function weeklySchedule()
    {
        return $this->hasMany(WeeklySchedule::class);
    }

    public function examSchedule()
    {
        return $this->hasMany(ExamSchedule::class);
    }

    public function collegeContract()
    {
        return $this->hasOne(CollegeContract::class);
    }

    public function score()
    {
        return $this->hasMany(Score::class);
    }
}
