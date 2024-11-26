<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Mtvs\EloquentHashids\HasHashid;
use Mtvs\EloquentHashids\HashidRouting;

class StudentStatus extends Model
{
    use HasFactory;

    protected $fillable = ['id', 'name', 'is_submited', 'is_active', 'is_college', 'is_default'];
    public $keyType = "string";

    public function studentCollegeActivity()
    {
        return $this->hasMany(StudentCollegeActivity::class);
    }
    public function graduation()
    {
        return $this->hasMany(Graduation::class);
    }
}
