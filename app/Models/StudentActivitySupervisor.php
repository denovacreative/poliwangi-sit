<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentActivitySupervisor extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $keyType = 'string';

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function studentActivity()
    {
        return $this->belongsTo(StudentActivity::class);
    }

    public function activityCategory()
    {
        return $this->belongsTo(ActivityCategory::class);
    }
}
