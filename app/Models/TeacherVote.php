<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TeacherVote extends Model
{
    use HasFactory;

    protected $fillable = [
        'teacher_id',
        'user_id',
        'quality',
        'attitude',
        'performance',
        'comment'
    ];
}
