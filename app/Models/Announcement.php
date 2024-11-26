<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Mtvs\EloquentHashids\HasHashid;
use Mtvs\EloquentHashids\HashidRouting;

class Announcement extends Model
{
    use HasFactory, HasHashid, HashidRouting;

    protected $guarded = [];
    protected $appends = ['hashid', 'formatted_time', 'formatted_date'];

    public function major()
    {
        return $this->belongsTo(Major::class);
    }

    public function studyProgram()
    {
        return $this->belongsTo(StudyProgram::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function getFormattedTimeAttribute()
    {
        return $this->created_at ? $this->created_at->format('H:i') : '-';
    }

    public function getFormattedDateAttribute()
    {
        return $this->created_at ? $this->created_at->format('d-m-Y') : '-';
    }
}
