<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Mtvs\EloquentHashids\HasHashid;
use Mtvs\EloquentHashids\HashidRouting;

class Student extends Model
{
    use HasFactory;

    protected $guarded = [];
    // protected $appends = ['hashid'];
    public $keyType = 'string';

    public function studyProgram()
    {
        return $this->belongsTo(StudyProgram::class);
    }


    public function studentStatus()
    {
        return $this->belongsTo(StudentStatus::class);
    }

    public function academicPeriod()
    {
        return $this->belongsTo(AcademicPeriod::class);
    }

    public function judicialParticipant()
    {
        return $this->hasOne(JudicialParticipant::class);
    }

    public function classGroup()
    {
        return $this->belongsTo(ClassGroup::class);
    }

    public function studentActivityMember()
    {
        return $this->hasMany(StudentActivityMember::class);
    }
    public function users()
    {
        return $this->morphOne(User::class, 'userable');
    }

    public function studentCollegeActivity()
    {
        return $this->hasMany(StudentCollegeActivity::class);
    }
    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function classParticipant()
    {
        return $this->hasMany(ClassParticipant::class);
    }

    public function fatherEducation()
    {
        return $this->belongsTo(EducationLevel::class, 'father_education_id', 'id');
    }

    public function fatherProfession()
    {
        return $this->belongsTo(Profession::class, 'father_profession_id', 'id');
    }

    public function fatherIncome()
    {
        return $this->belongsTo(Income::class, 'father_income_id', 'id');
    }

    public function motherEducation()
    {
        return $this->belongsTo(EducationLevel::class, 'mother_education_id', 'id');
    }

    public function motherProfession()
    {
        return $this->belongsTo(Profession::class, 'mother_profession_id', 'id');
    }

    public function motherIncome()
    {
        return $this->belongsTo(Income::class, 'mother_income_id', 'id');
    }

    public function guardianEducation()
    {
        return $this->belongsTo(EducationLevel::class, 'guardian_education_id', 'id');
    }

    public function guardianship()
    {
        return $this->hasMany(Guardianship::class);
    }

    public function guardianProfession()
    {
        return $this->belongsTo(Profession::class, 'guardian_profession_id', 'id');
    }

    public function guardianIncome()
    {
        return $this->belongsTo(Income::class, 'guardian_income_id', 'id');
    }

    public function schoolRegion()
    {
        return $this->belongsTo(Region::class, 'school_region_id', 'id');
    }

    public function consentration()
    {
        return $this->belongsTo(Consentration::class);
    }

    public function lectureSystem()
    {
        return $this->belongsTo(LectureSystem::class);
    }

    public function registrationType()
    {
        return $this->belongsTo(RegistrationType::class);
    }

    public function religion()
    {
        return $this->belongsTo(Religion::class);
    }

    public function ethnic()
    {
        return $this->belongsTo(Ethnic::class);
    }

    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    public function transportation()
    {
        return $this->belongsTo(Transportation::class);
    }

    public function profession()
    {
        return $this->belongsTo(Profession::class);
    }

    public function income()
    {
        return $this->belongsTo(Income::class);
    }

    public function region()
    {
        return $this->belongsTo(Region::class);
    }

    public function registrationPath()
    {
        return $this->belongsTo(RegistrationPath::class);
    }

    public function originSchool()
    {
        return $this->belongsTo(OriginSchool::class);
    }

    // public function judicialParticipant(){
    //     return $this->belongsTo(JudicialParticipant::class);
    // }

    public function typeOfStay()
    {
        return $this->belongsTo(TypeOfStay::class);
    }

    public function curriculum()
    {
        return $this->belongsTo(Curriculum::class);
    }

    public function studentDisability()
    {
        return $this->hasMany(StudentDisability::class);
    }

    public function score()
    {
        return $this->hasMany(Score::class);
    }

    public function presence()
    {
        return $this->hasMany(Presence::class);
    }

    public function heregistration()
    {
        return $this->hasMany(Heregistration::class);
    }

    public function scholarship()
    {
        return $this->belongsToMany(Scholarship::class, 'student_scholarships');
    }

    public function collegeClasses()
    {
        return $this->classParticipant()->with(['collegeClass']);
    }

    public function graduation()
    {
        return $this->hasOne(Graduation::class);
    }
    public function thesis()
    {
        return $this->hasMany(Thesis::class);
    }
}
