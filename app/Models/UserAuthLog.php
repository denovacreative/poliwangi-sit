<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserAuthLog extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'role', 'ip_address', 'user_agent', 'number', 'signin_at', 'user_id'];

}
