<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Major extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'is_active'
    ];

    protected $hidden = [
        'is_active',
        'created_at',
    ];
}
