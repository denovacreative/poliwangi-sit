<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Mtvs\EloquentHashids\HasHashid;
use Mtvs\EloquentHashids\HashidRouting;

class ThesisRequirement extends Model
{
    use HasFactory, HasHashid, HashidRouting;

    protected $fillable = ['name', 'is_active', 'is_upload', 'description', 'thesis_type', 'thesis_stage_id'];
    protected $appends = ['hashid'];
    protected $hidden = ['id'];

    public function thesisStage()
    {
        return $this->belongsTo(ThesisStage::class);
    }
}
