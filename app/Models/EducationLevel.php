<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Mtvs\EloquentHashids\HasHashid;
use Mtvs\EloquentHashids\HashidRouting;

class EducationLevel extends Model
{
    use HasFactory, HasHashid, HashidRouting;

    protected $guarded = [];
    protected $appends = ['hashid'];

    public function studyProgram()
    {
        return $this->hasMany(StudyProgram::class);
    }

    public function educationLevelSetting()
    {
        return $this->hasOne(EducationLevelSetting::class);
    }

    public function diplomaCompanion()
    {
        return $this->hasMany(DiplomaCompanion::class);
    }
}
