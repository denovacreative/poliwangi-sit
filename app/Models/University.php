<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Mtvs\EloquentHashids\HasHashid;
use Mtvs\EloquentHashids\HashidRouting;

class University extends Model
{
    use HasFactory, HashidRouting, HasHashid;

    protected $fillable = ['code', 'name', 'phone_number', 'fax', 'email', 'website', 'address'];
    protected $appends = ['hashid'];
    protected $hidden = ['id'];

}
