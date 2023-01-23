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

    public function major(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Major::class);
    }

    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
