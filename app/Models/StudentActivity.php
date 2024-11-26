<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentActivity extends Model
{
    use HasFactory;

    protected $guarded = [];
    protected $keyType = 'string';

    public function activityScoreConversion()
    {
        return $this->hasMany(ActivityScoreConversion::class);
    }

    public function studentActivityMember()
    {
        return $this->hasMany(StudentActivityMember::class);
    }

    public function academicPeriod()
    {
        return $this->belongsTo(AcademicPeriod::class);
    }

    public function studyProgram()
    {
        return $this->belongsTo(StudyProgram::class);
    }

    public function studentActivityCategory()
    {
        return $this->belongsTo(StudentActivityCategory::class);
    }
    public function studentActivitySupervisor()
    {
        return $this->hasMany(StudentActivitySupervisor::class);
    }
}
