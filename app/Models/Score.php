<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Mtvs\EloquentHashids\HasHashid;
use Mtvs\EloquentHashids\HashidRouting;

class Score extends Model
{
    use HasFactory;

    protected $guarded = [];
    public $keyType = 'string';

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function collegeClass()
    {
        return $this->belongsTo(CollegeClass::class);
    }


    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}
