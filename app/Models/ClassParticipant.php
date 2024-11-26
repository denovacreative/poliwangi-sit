<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClassParticipant extends Model
{
    use HasFactory;
    protected $guarded = [];
    public $keyType = 'string';
    public $incrementing = false;

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function collegeClass()
    {
        return $this->belongsTo(CollegeClass::class);
    }
}
