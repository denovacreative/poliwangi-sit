<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Mtvs\EloquentHashids\HasHashid;
use Mtvs\EloquentHashids\HashidRouting;

class Religion extends Model
{
    use HasFactory, HasHashid, HashidRouting;

    protected $hidden = ['id'];
    protected $appends = ['hashid'];
    protected $fillable = ['id','name'];

    public function contactPerson()
    {
        return $this->hasMany(ContactPerson::class);
    }

}
