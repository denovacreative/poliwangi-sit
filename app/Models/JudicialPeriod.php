<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JudicialPeriod extends Model
{
    use HasFactory;

    protected $fillable = ['id', 'academic_period_id', 'periode', 'name', 'date', 'date_start', 'date_end'];
    public $keyType = 'string';

    public function academicPeriod()
    {
        return $this->belongsTo(AcademicPeriod::class);
    }

    public function judicialRequirement()
    {
        return $this->hasMany(JudicialRequirement::class);
    }
}
