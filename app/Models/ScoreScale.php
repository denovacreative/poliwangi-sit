<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ScoreScale extends Model
{
    use HasFactory;

    protected $guarded = [];
    public $keyType = 'string';

    public function studyProgram()
    {
        return $this->belongsTo(StudyProgram::class);
    }
}
