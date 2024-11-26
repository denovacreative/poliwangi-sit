<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActivityScoreConversion extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $keyType = 'string';

    public function course()
    {
        return $this->belongsTo(Course::class);
    }
    public function studentActivityMember()
    {
        return $this->belongsTo(StudentActivityMember::class);
    }
    public function studentActivity()
    {
        return $this->belongsTo(StudentActivity::class);
    }
}
