<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Mtvs\EloquentHashids\HasHashid;
use Mtvs\EloquentHashids\HashidRouting;

class ContactPerson extends Model
{
    use HasFactory, HasHashid, HashidRouting;

    protected $table = "contact_persons";
    protected $fillable = ['name', 'front_title', 'back_title', 'gender', 'phone_number', 'email', 'address', 'agency_id', 'religion_id'];
    protected $appends = ['hashid'];
    protected $hidden = ['id'];

    public function agency()
    {
        return $this->belongsTo(Agency::class);
    }

    public function religion()
    {
        return $this->belongsTo(Religion::class);
    }
}
