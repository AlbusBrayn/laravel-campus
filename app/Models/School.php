<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class School extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email_pattern',
        'latitude',
        'longitude',
        'latitude_delta',
        'longitude_delta',
        'is_active'
    ];

    protected $hidden = [
        'is_active',
        'email_pattern'
    ];

    public function users()
    {
        $this->hasMany(User::class);
    }
}
