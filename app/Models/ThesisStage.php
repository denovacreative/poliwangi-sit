<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ThesisStage extends Model
{
    use HasFactory;

    public function thesisRequirement()
    {
        return $this->hasMany(ThesisRequirement::class);
    }

    public function thesis()
    {
        return $this->hasMany(Thesis::class);
    }
}
