<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JudicialParticipant extends Model
{
    use HasFactory;

    protected $guarded = [''];
    public $keyType = 'string';

    public function judicialPeriod()
    {
        return $this->belongsTo(JudicialPeriod::class);
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function judicialParticipantRequirement()
    {
        return $this->hasMany(JudicialParticipantRequirement::class, 'judicial_participant_id', 'id');
    }
}
