<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Mtvs\EloquentHashids\HasHashid;
use Mtvs\EloquentHashids\HashidRouting;
use Illuminate\Support\Str;
use Exception;

class Course extends Model
{
    use HasFactory;

    protected $guarded = [];
    public $keyType = "string";
    public $incrementing = false;

    public function courseType()
    {
        return $this->belongsTo(CourseType::class);
    }

    public function courseGroup()
    {
        return $this->belongsTo(CourseGroup::class);
    }

    public function scientificField()
    {
        return $this->belongsTo(ScientificField::class);
    }

    public function studyProgram()
    {
        return $this->belongsTo(StudyProgram::class);
    }

    public function activityScoreConversion()
    {
        return $this->hasMany(ActivityScoreConversion::class);
    }

    public function collegeClass()
    {
        return $this->hasMany(CollegeClass::class);
    }
}
