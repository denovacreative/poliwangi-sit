<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JudicialRequirement extends Model
{
    use HasFactory;

    protected $guarded = [];
    protected $keyType = 'string';

    public function judicialPeriod()
    {
        return $this->belongsTo(JudicialPeriod::class);
    }
}
