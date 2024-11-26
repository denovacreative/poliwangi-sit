<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Major extends Model
{
    use HasFactory;

    protected $guarded = [];
    public $keyType = "string";

    protected static function boot()
    {
        parent::boot();

        static::creating(function($model) {
            try {
                $model->id = (string) Str::uuid();
            } catch(Exception $e) {
                abort(500, $e->getMessage());
            }
        });
    }

    public function employee(){
        return $this->belongsTo(Employee::class);
    }

    public function studyProgram()
    {
        return $this->hasMany(StudyProgram::class);
    }

    public function room()
    {
        return $this->morphMany(Room::class, 'unitable');
    }
}
