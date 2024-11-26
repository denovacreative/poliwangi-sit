<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AchievementField extends Model
{
    use HasFactory;

    public function achievementGroup()
    {
        return $this->hasMany(AchievementGroup::class);
    }
}
