<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Mtvs\EloquentHashids\HasHashid;
use Mtvs\EloquentHashids\HashidRouting;

class AchievementGroup extends Model
{
    use HasFactory, HasHashid, HashidRouting;
    protected $fillable = ['name', 'point', 'is_active', 'achievement_field_id', 'achievement_type_id'];
    protected $appends = ['hashid'];
    // protected $hidden = ['id'];

    public function achievementField()
    {
        return $this->belongsTo(AchievementField::class);
    }
    public function achievementType()
    {
        return $this->belongsTo(AchievementType::class);
    }
}
