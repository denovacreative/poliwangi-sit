<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Mtvs\EloquentHashids\HasHashid;
use Mtvs\EloquentHashids\HashidRouting;

class ScholarshipType extends Model
{
    use HasFactory, HasHashid, HashidRouting;
    protected $fillable = ['name'];
    protected $appends = ['hashid'];
    protected $hidden = ['id'];

    public function scholarship()
    {
        return $this->hasMany(Scholarship::class);
    }
}
