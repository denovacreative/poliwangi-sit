<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentCollegeActivity extends Model
{
    use HasFactory;
    protected $guarded = [];
    public $keyType = 'string';

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function studentStatus()
    {
        return $this->belongsTo(StudentStatus::class);
    }

    public function academicPeriod()
    {
        return $this->belongsTo(AcademicPeriod::class);
    }
}
