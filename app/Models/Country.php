<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Mtvs\EloquentHashids\HasHashid;
use Mtvs\EloquentHashids\HashidRouting;

class Country extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'id'];
    public $incrementing = false;
    public $keyType = 'string';

}
