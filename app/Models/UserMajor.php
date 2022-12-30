<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserMajor extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'school_id',
        'major_id'
    ];
}
