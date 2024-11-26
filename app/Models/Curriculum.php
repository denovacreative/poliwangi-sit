<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\StudyProgram;

class Curriculum extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $table = "curriculums";
    public $incrementing = false;
    public $keyType = 'string';

    public function course()
    {
        return $this->belongsToMany(Course::class, 'course_curriculums', 'curriculum_id', 'course_id');
    }

    public function courseCurriculum()
    {
        return $this->hasMany(CourseCurriculum::class);
    }

    public function academicPeriod()
    {
        return $this->belongsTo(AcademicPeriod::class);
    }

    public function studyProgram()
    {
        return $this->belongsTo(StudyProgram::class);
    }
    public function student()
    {
        return $this->hasMany(Student::class);
    }
}
