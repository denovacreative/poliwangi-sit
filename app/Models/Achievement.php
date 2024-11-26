<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Mtvs\EloquentHashids\HasHashid;
use Mtvs\EloquentHashids\HashidRouting;

class Achievement extends Model
{
    use HasFactory;

    protected $guarded = [];
    public $keyType = 'string';
    public $incrementing = false;

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function achievementLevel()
    {
        return $this->belongsTo(AchievementLevel::class);
    }

    public function academicPeriod()
    {
        return $this->belongsTo(AcademicPeriod::class);
    }

    public function achievementType()
    {
        return $this->belongsTo(AchievementType::class);
    }

    public function achievementGroup()
    {
        return $this->belongsTo(AchievementGroup::class);
    }
}
