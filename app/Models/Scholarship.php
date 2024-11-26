<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Scholarship extends Model
{
    use HasFactory;

    protected $keyType = 'string';
    public $incrementing = false;
    protected $guarded = [];

    public function scholarshipType()
    {
        return $this->belongsTo(ScholarshipType::class);
    }

    public function periodStart()
    {
        return $this->belongsTo(AcademicPeriod::class, 'period_start_id');
    }
    public function periodEnd()
    {
        return $this->belongsTo(AcademicPeriod::class, 'period_end_id');
    }

    public function studentScholarship()
    {
        return $this->hasMany(StudentScholarship::class);
    }
}
