<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Mtvs\EloquentHashids\HasHashid;
use Mtvs\EloquentHashids\HashidRouting;

class AcademicPeriod extends Model
{
    use HasFactory, HasHashid, HashidRouting;

    protected $fillable = [
        'id',
        'academic_year_id',
        'semester',
        'name',
        'college_start_date',
        'college_end_date',
        'mid_exam_start_date',
        'mid_exam_end_date',
        'final_exam_start_date',
        'final_exam_end_date',
        'heregistration_start_date',
        'heregistration_end_date',
        'number_of_meeting',
        'is_active',
        'is_use'
    ];
    protected $appends = ['hashid'];

    public function studyProgram()
    {
        return $this->hasMany(StudyProgram::class);
    }
    public function academicYear()
    {
        return $this->belongsTo(AcademicYear::class);
    }

    public function judicialPeriod()
    {
        return $this->hasMany(JudicialPeriod::class);
    }

    public function student()
    {
        return $this->hasMany(Student::class);
    }

    public function studentActivity()
    {
        return $this->hasMany(StudentActivity::class);
    }
    public function studentCollegeActivity()
    {
        return $this->hasMany(StudentCollegeActivity::class);
    }

    public function studentScholarship()
    {
        return $this->hasMany(AcademicPeriod::class);
    }

    public function scholarship()
    {
        return $this->hasMany(Scholarship::class);
    }
    public function graduation()
    {
        return $this->hasMany(Graduation::class);
    }

    public function thesis()
    {
        return $this->hasMany(Thesis::class);
    }

    public function heregistration()
    {
        return $this->hasMany(Heregistration::class);
    }
}
