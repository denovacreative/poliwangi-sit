<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Mtvs\EloquentHashids\HasHashid;
use Mtvs\EloquentHashids\HashidRouting;

class DiplomaCompanion extends Model
{
    use HasFactory, HasHashid, HashidRouting;
    protected $fillable = [
        'study_program_id',
        'education_level_id',
        'terms_acceptance',
        'terms_acceptance_en',
        'study',
        'type_education',
        'type_education_en',
        'next_type_education',
        'next_type_education_en',
        'kkni_level',
        'profession_status',
        'instruction_language',
        'instruction_language_en',
        'introduction',
        'introduction_en',
        'kkni_info',
        'kkni_info_en',
        'work_ability',
        'work_ability_en',
        'mastery_of_knowledge',
        'mastery_of_knowledge_en',
        'special_attitude',
        'special_attitude_en',
    ];
    protected $appends = ['hashid'];
    protected $hidden = ['id'];

    public function studyProgram()
    {
        return $this->belongsTo(StudyProgram::class);
    }

    public function educationLevel()
    {
        return $this->belongsTo(EducationLevel::class);
    }
}
