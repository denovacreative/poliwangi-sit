<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ThesisGuidance extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $keyType = 'string';

    public function thesis()
    {
        return $this->belongsTo(Thesis::class);
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}
