<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Courses extends Model
{
    use HasFactory;

    protected $fillable = [
        
        'school_id',
        'major_id',
        'name',
        'is_active'
    ];

    public function school(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(School::class);
    }

    public function major(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Major::class);
    }
}
