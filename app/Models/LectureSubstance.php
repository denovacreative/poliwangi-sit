<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LectureSubstance extends Model
{
    use HasFactory;

    public function teachingLecturer()
    {
        return $this->hasMany(TeachingLecturer::class);
    }
}
