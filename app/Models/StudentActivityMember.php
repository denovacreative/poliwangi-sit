<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentActivityMember extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $keyType = 'string';

    public function activityScoreConversion()
    {
        return $this->hasMany(ActivityScoreConversion::class);
    }

    public function studentActivity()
    {
        return $this->belongsTo(StudentActivity::class);
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}
