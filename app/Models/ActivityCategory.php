<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActivityCategory extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $keyType = 'string';

    public function studentActivitySupervisor()
    {
        return $this->hasMany(StudentActivitySupervisor::class);
    }
}
