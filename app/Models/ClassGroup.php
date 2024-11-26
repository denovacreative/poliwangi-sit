<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Mtvs\EloquentHashids\HasHashid;
use Mtvs\EloquentHashids\HashidRouting;

class ClassGroup extends Model
{
    use HasFactory, HasHashid, HashidRouting;

    protected $fillable = ['code', 'name', 'academic_year_id', 'study_program_id'];
    protected $appends = ['hashid'];
    protected $hidden = ['id'];

    public function academicYear()
    {
        return $this->belongsTo(AcademicYear::class);
    }

    public function studyProgram()
    {
        return $this->belongsTo(StudyProgram::class);
    }
}
