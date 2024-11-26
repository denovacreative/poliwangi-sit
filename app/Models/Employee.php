<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;

    protected $guarded = [];

    public $incrementing = false;
    public $keyType = "string";
    public $appends = ['complete_regions'];

    public function employeeStatus()
    {
        return $this->belongsTo(EmployeeStatus::class);
    }

    public function guardianship()
    {
        return $this->hasMany(Guardianship::class);
    }

    public function employeeActiveStatus()
    {
        return $this->belongsTo(EmployeeActiveStatus::class);
    }

    public function employeeType()
    {
        return $this->belongsTo(EmployeeType::class);
    }

    public function university()
    {
        return $this->belongsTo(University::class);
    }

    public function scientificField()
    {
        return $this->belongsTo(ScientificField::class);
    }

    public function religion()
    {
        return $this->belongsTo(Religion::class);
    }

    public function familyProfession()
    {
        return $this->belongsTo(Profession::class);
    }

    public function region()
    {
        return $this->belongsTo(Region::class);
    }

    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    public function getCompleteRegionsAttribute()
    {
        return $this->region()->with(['parent' => function ($q) {
            $q->with('parent');
        }])->first() ?? '';
    }

    public function teachingLecturer()
    {
        return $this->hasMany(TeachingLecturer::class);
    }

    public function users()
    {
        return $this->morphOne(User::class, 'userable');
    }

    public function student()
    {
        return $this->hasMany(Student::class);
    }

    public function studentActivitySupervisor()
    {
        return $this->hasMany(StudentActivitySupervisor::class);
    }

    public function thesisGuidance()
    {
        return $this->hasMany(ThesisGuidance::class);
    }
}
