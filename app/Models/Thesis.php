<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Thesis extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $keyType = 'string';

    public function academicPeriod()
    {
        return $this->belongsTo(AcademicPeriod::class);
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function thesisStage()
    {
        return $this->belongsTo(ThesisStage::class);
    }

    public function thesisGuidance()
    {
        return $this->hasMany(ThesisGuidance::class);
    }
}
