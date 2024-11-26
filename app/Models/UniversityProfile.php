<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UniversityProfile extends Model
{
    use HasFactory;

    protected $guarded = [];
    public $keyType = "string";

    public function employee() {
        return $this->belongsTo(Employee::class);
    }

    public function viceChancellor() {
        return $this->belongsTo(Employee::class, 'vice_chancellor', 'id');
    }

    public function viceChancellor2() {
        return $this->belongsTo(Employee::class, 'vice_chancellor_2', 'id');
    }

    public function viceChancellor3() {
        return $this->belongsTo(Employee::class, 'vice_chancellor_3', 'id');
    }
}
