<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    use HasFactory;

   
    protected $fillable = [
        'user_id',
        'subject',
        'message',
        'is_read',
        'is_answered',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
