<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Mtvs\EloquentHashids\HasHashid;
use Mtvs\EloquentHashids\HashidRouting;

class AchievementType extends Model
{
    use HasFactory, HasHashid, HashidRouting;

    protected $fillable = ['code', 'name'];
    protected $appends = ['hashid'];
    protected $hidden = ['id'];

    public function achievementGroup()
    {
        return $this->hasMany(AchievementGroup::class);
    }
}
